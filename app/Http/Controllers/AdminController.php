<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Modulo;
use App\Models\Aula;
use App\Models\Usuario;
use App\Models\Progresso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalColaboradores = Usuario::where('perfil', 'colaborador')->count();
        $totalCursos = Curso::count();

        $progressos = Progresso::with('curso', 'usuario')->get();
        $taxaConclusao = $progressos->count() > 0 ? (int) round($progressos->avg('porcentagem')) : 0;

        $pendentes = $progressos->where('porcentagem', '<', 100)->count();

        $concluidos = $progressos->where('porcentagem', '>=', 100)->count();
        $emAndamento = $progressos->whereBetween('porcentagem', [1, 99])->count();
        $naoIniciados = max(($totalColaboradores * $totalCursos) - $progressos->count(), 0);
        $totalDistribuicao = max($concluidos + $emAndamento + $naoIniciados, 1);

        $distribuicao = [
            'concluidos' => round($concluidos / $totalDistribuicao * 100),
            'em_andamento' => round($emAndamento / $totalDistribuicao * 100),
            'nao_iniciados' => round($naoIniciados / $totalDistribuicao * 100),
        ];

        $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul'];
        $atividadeMensal = [];
        foreach ($meses as $indice => $mes) {
            $atividadeMensal[$mes] = $progressos->filter(function ($p) use ($indice) {
                return (int) $p->created_at->format('n') === $indice + 1;
            })->count();
        }

        $cursosMaisAcessados = $progressos->groupBy('curso_id')
            ->map(function ($grupo) {
                return [
                    'titulo' => $grupo->first()->curso->titulo,
                    'categoria' => $grupo->first()->curso->categoria,
                    'percentual' => (int) round($grupo->avg('porcentagem')),
                ];
            })
            ->sortByDesc('percentual')
            ->take(4);

        $colaboradoresComPendencias = $progressos->where('porcentagem', '<', 100)
            ->sortBy('porcentagem')
            ->take(3)
            ->map(function ($p) {
                return [
                    'nome' => $p->usuario->nome,
                    'area' => $p->usuario->area,
                    'percentual' => $p->porcentagem,
                    'status' => $p->porcentagem > 0 ? 'Em andamento' : 'Não iniciado',
                ];
            });

        return view('admin.dashboard', compact(
            'totalColaboradores', 'totalCursos', 'taxaConclusao', 'pendentes',
            'distribuicao', 'atividadeMensal', 'cursosMaisAcessados', 'colaboradoresComPendencias'
        ));
    }

    public function cursos()
    {
        $cursos = Curso::withCount('modulos')->orderBy('titulo')->get();

        return view('admin.cursos', compact('cursos'));
    }

    public function cursosGerenciar(Curso $curso)
    {
        $curso->load(['modulos' => function ($query) {
            $query->orderBy('ordem')->with(['aulas' => function ($query) {
                $query->orderBy('ordem');
            }]);
        }]);

        return view('admin.curso-gerenciar', compact('curso'));
    }

    public function cursosStore(Request $request)
    {
        $dados = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'categoria' => ['required', 'string', 'max:255'],
            'instrutor' => ['nullable', 'string', 'max:255'],
            'carga_horaria' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'string', 'in:Não iniciado,Em andamento,Concluído'],
            'descricao' => ['nullable', 'string'],
            'capa' => ['nullable', 'image', 'max:4096'],
            'fundo' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('capa')) {
            $dados['capa'] = $request->file('capa')->store('cursos/capas', 'public');
        }
        if ($request->hasFile('fundo')) {
            $dados['fundo'] = $request->file('fundo')->store('cursos/fundos', 'public');
        }

        Curso::create($dados);

        return redirect()->route('admin.cursos')->with('status', 'Curso criado com sucesso.');
    }

    public function cursosUpdate(Request $request, Curso $curso)
    {
        $dados = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'categoria' => ['required', 'string', 'max:255'],
            'instrutor' => ['nullable', 'string', 'max:255'],
            'carga_horaria' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'string', 'in:Não iniciado,Em andamento,Concluído'],
            'descricao' => ['nullable', 'string'],
            'capa' => ['nullable', 'image', 'max:4096'],
            'fundo' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('capa')) {
            if ($curso->capa) {
                Storage::disk('public')->delete($curso->capa);
            }
            $dados['capa'] = $request->file('capa')->store('cursos/capas', 'public');
        }
        if ($request->hasFile('fundo')) {
            if ($curso->fundo) {
                Storage::disk('public')->delete($curso->fundo);
            }
            $dados['fundo'] = $request->file('fundo')->store('cursos/fundos', 'public');
        }

        $curso->update($dados);

        return redirect()->route('admin.cursos')->with('status', 'Curso atualizado com sucesso.');
    }

    public function cursosDestroy(Curso $curso)
    {
        $curso->delete();

        return redirect()->route('admin.cursos')->with('status', 'Curso removido com sucesso.');
    }

    public function moduloStore(Request $request, Curso $curso)
    {
        $dados = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'ordem' => ['nullable', 'integer', 'min:1'],
            'capa' => ['nullable', 'image', 'max:4096'],
            'fundo' => ['nullable', 'image', 'max:4096'],
        ]);

        $dados['id_curso'] = $curso->id_curso;
        $dados['ordem'] = $dados['ordem'] ?? ($curso->modulos()->max('ordem') + 1);

        if ($request->hasFile('capa')) {
            $dados['capa'] = $request->file('capa')->store('modulos/capas', 'public');
        }
        if ($request->hasFile('fundo')) {
            $dados['fundo'] = $request->file('fundo')->store('modulos/fundos', 'public');
        }

        Modulo::create($dados);

        return redirect()->route('admin.cursos.gerenciar', $curso->id_curso)->with('status', 'Módulo criado com sucesso.');
    }

    public function moduloUpdate(Request $request, Modulo $modulo)
    {
        $dados = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'ordem' => ['nullable', 'integer', 'min:1'],
            'capa' => ['nullable', 'image', 'max:4096'],
            'fundo' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('capa')) {
            if ($modulo->capa) {
                Storage::disk('public')->delete($modulo->capa);
            }
            $dados['capa'] = $request->file('capa')->store('modulos/capas', 'public');
        }
        if ($request->hasFile('fundo')) {
            if ($modulo->fundo) {
                Storage::disk('public')->delete($modulo->fundo);
            }
            $dados['fundo'] = $request->file('fundo')->store('modulos/fundos', 'public');
        }

        $modulo->update($dados);

        return redirect()->route('admin.cursos.gerenciar', $modulo->id_curso)->with('status', 'Módulo atualizado com sucesso.');
    }

    public function moduloDestroy(Modulo $modulo)
    {
        $idCurso = $modulo->id_curso;
        $modulo->delete();

        return redirect()->route('admin.cursos.gerenciar', $idCurso)->with('status', 'Módulo removido com sucesso.');
    }

    public function aulaStore(Request $request, Modulo $modulo)
    {
        $dados = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'url_video' => ['required', 'url', 'max:255'],
            'duracao_minutos' => ['nullable', 'integer', 'min:1'],
            'ordem' => ['nullable', 'integer', 'min:1'],
        ]);

        $dados['id_modulo'] = $modulo->id_modulo;
        $dados['ordem'] = $dados['ordem'] ?? ($modulo->aulas()->max('ordem') + 1);

        Aula::create($dados);

        return redirect()->route('admin.cursos.gerenciar', $modulo->id_curso)->with('status', 'Aula criada com sucesso.');
    }

    public function aulaUpdate(Request $request, Aula $aula)
    {
        $dados = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'url_video' => ['required', 'url', 'max:255'],
            'duracao_minutos' => ['nullable', 'integer', 'min:1'],
            'ordem' => ['nullable', 'integer', 'min:1'],
        ]);

        $aula->update($dados);

        return redirect()->route('admin.cursos.gerenciar', $aula->modulo->id_curso)->with('status', 'Aula atualizada com sucesso.');
    }

    public function aulaDestroy(Aula $aula)
    {
        $idCurso = $aula->modulo->id_curso;
        $aula->delete();

        return redirect()->route('admin.cursos.gerenciar', $idCurso)->with('status', 'Aula removida com sucesso.');
    }

    public function colaboradores(Request $request)
    {
        $busca = $request->query('busca');
        $area = $request->query('area');

        $query = Usuario::where('perfil', 'colaborador')->with(['progressos.curso']);

        if ($busca) {
            $query->where('nome', 'like', "%{$busca}%");
        }
        if ($area) {
            $query->where('area', $area);
        }

        $colaboradores = $query->orderBy('nome')->get()->map(function ($usuario) {
            $progresso = $usuario->progressos->sortByDesc('porcentagem')->first();
            $usuario->percentual = $progresso->porcentagem ?? 0;
            $usuario->status = $usuario->percentual >= 100 ? 'Concluído' : ($usuario->percentual > 0 ? 'Em andamento' : 'Não iniciado');
            return $usuario;
        });

        $areas = Usuario::where('perfil', 'colaborador')->select('area')->distinct()->orderBy('area')->pluck('area');

        return view('admin.colaboradores', compact('colaboradores', 'areas', 'busca', 'area'));
    }

    public function colaboradoresStore(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:usuarios,email'],
            'cargo' => ['nullable', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
        ]);

        Usuario::create([
            ...$dados,
            'password' => Hash::make('senha123'),
            'perfil' => 'colaborador',
            'status' => 'ativo',
        ]);

        return redirect()->route('admin.colaboradores')->with('status', 'Colaborador cadastrado com sucesso.');
    }
    public function index()
    {
        $usuario = auth()->user();

        // Substitua 'admin.perfil' pelo nome exato do seu arquivo blade de perfil
        return view('admin.perfil', compact('usuario'));
    }
}