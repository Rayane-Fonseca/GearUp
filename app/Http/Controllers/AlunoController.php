<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Trilha;
use App\Models\Progresso;
use App\Models\Certificado;
use App\Models\Notificacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AlunoController extends Controller
{
    public function inicio()
    {
        $usuario = Auth::user();

        $progressos = Progresso::with('curso')
            ->where('usuario_id', $usuario->id_usuario)
            ->get();

        $emAndamento = $progressos->filter(fn($p) => $p->porcentagem > 0 && $p->porcentagem < 100);
        $concluidos = $progressos->filter(fn($p) => $p->porcentagem >= 100);

        $progressoGeral = $progressos->count() > 0
            ? (int) round($progressos->avg('porcentagem'))
            : 0;

        $horasTotais = $progressos->sum(fn($p) => $p->curso->carga_horaria ?? 0);
        $certificadosCount = Certificado::where('id_usuario', $usuario->id_usuario)->count();

        $recomendado = Curso::whereNotIn('id_curso', $progressos->pluck('curso_id'))->inRandomOrder()->first();

        $notificacoes = Notificacao::where('usuario_id', $usuario->id_usuario)
            ->latest()
            ->take(3)
            ->get();

        // Cursos obrigatórios para a área de atuação do aluno que ainda não foram concluídos
        $progressoPorCurso = $progressos->keyBy('curso_id');
        $cursosObrigatoriosPendentes = $usuario->area
            ? Curso::where('categoria', $usuario->area)
                ->get()
                ->filter(fn ($curso) => ($progressoPorCurso[$curso->id_curso]->porcentagem ?? 0) < 100)
                ->values()
            : collect();

        return view('aluno.inicio', compact(
            'usuario',
            'emAndamento',
            'concluidos',
            'progressoGeral',
            'horasTotais',
            'certificadosCount',
            'recomendado',
            'notificacoes',
            'cursosObrigatoriosPendentes'
        ));
    }

    public function cursos(Request $request)
    {
        $usuario = Auth::user();
        $categoria = $request->query('categoria');

        $query = Curso::query();
        if ($categoria && $categoria !== 'Todos') {
            $query->where('categoria', $categoria);
        }
        $cursos = $query->orderBy('titulo')->get();

        $progressosPorCurso = Progresso::where('usuario_id', $usuario->id_usuario)
            ->pluck('porcentagem', 'curso_id');

        // Marca quais cursos são obrigatórios para a área de atuação do aluno logado
        // e traz eles pendentes (não concluídos) para o topo da listagem.
        $cursos = $cursos
            ->map(function ($curso) use ($usuario, $progressosPorCurso) {
                $curso->obrigatorio = $curso->ehObrigatorioPara($usuario);
                $curso->percentual = $progressosPorCurso[$curso->id_curso] ?? 0;
                return $curso;
            })
            ->sortBy([
                fn ($curso) => $curso->obrigatorio && $curso->percentual < 100 ? 0 : 1,
                fn ($curso) => $curso->titulo,
            ])
            ->values();

        $categorias = Curso::select('categoria')->distinct()->orderBy('categoria')->pluck('categoria');

        return view('aluno.cursos', compact('cursos', 'progressosPorCurso', 'categorias', 'categoria'));
    }

    public function trilhas()
    {
        $usuario = Auth::user();
        $trilhas = Trilha::with('cursos')->where('ativo', true)->orderBy('titulo')->get();

        $progressosPorCurso = Progresso::where('usuario_id', $usuario->id_usuario)
            ->pluck('porcentagem', 'curso_id');

        $trilhas = $trilhas->map(function ($trilha) use ($progressosPorCurso) {
            $percentuais = $trilha->cursos->map(fn($c) => $progressosPorCurso[$c->id_curso] ?? 0);
            $trilha->progresso = $percentuais->count() > 0 ? (int) round($percentuais->avg()) : 0;
            $trilha->obrigatoriosCount = $trilha->cursos->where('pivot.obrigatorio', true)->count();
            $trilha->opcionaisCount = $trilha->cursos->where('pivot.obrigatorio', false)->count();
            return $trilha;
        });

        return view('aluno.trilhas', compact('trilhas'));
    }

    public function trilhaDetalhe($id)
    {
        $usuario = Auth::user();
        $trilha = Trilha::with('cursos')->findOrFail($id);

        $progressosPorCurso = Progresso::where('usuario_id', $usuario->id_usuario)
            ->pluck('porcentagem', 'curso_id');

        $obrigatorios = $trilha->cursos->where('pivot.obrigatorio', true);
        $opcionais = $trilha->cursos->where('pivot.obrigatorio', false);

        $percentuais = $trilha->cursos->map(fn($c) => $progressosPorCurso[$c->id_curso] ?? 0);
        $progressoTrilha = $percentuais->count() > 0 ? (int) round($percentuais->avg()) : 0;

        return view('aluno.trilha-detalhe', compact(
            'trilha',
            'obrigatorios',
            'opcionais',
            'progressosPorCurso',
            'progressoTrilha'
        ));
    }

    public function certificados()
    {
        $usuario = Auth::user();

        $certificados = Certificado::with('curso')
            ->where('id_usuario', $usuario->id_usuario)
            ->latest('emitido_em')
            ->get();

        $proximoCertificado = Progresso::with('curso')
            ->where('usuario_id', $usuario->id_usuario)
            ->where('porcentagem', '<', 100)
            ->orderByDesc('porcentagem')
            ->first();

        return view('aluno.certificados', compact('certificados', 'proximoCertificado'));
    }

    public function perfil()
    {
        $usuario = Auth::user();

        $progressos = Progresso::with('curso')
            ->where('usuario_id', $usuario->id_usuario)
            ->get();

        $cursosCount = $progressos->count();
        $horasTotais = $progressos->sum(fn($p) => $p->curso->carga_horaria ?? 0);
        $certificadosCount = Certificado::where('id_usuario', $usuario->id_usuario)->count();

        $progressoPorArea = $progressos
            ->groupBy(fn($p) => $p->curso->categoria ?? 'Outros')
            ->map(fn($grupo) => (int) round($grupo->avg('porcentagem')));

        return view('aluno.perfil', compact(
            'usuario',
            'cursosCount',
            'horasTotais',
            'certificadosCount',
            'progressoPorArea'
        ));
    }
    public function notificacoes()
    {
        $notificacoes = auth()->user()->notificacoes; // ou a sua lógica
        return view('aluno.notificacoes', compact('notificacoes'));
    }
    public function update(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'different:current_password',
                Password::min(8)->numbers()->symbols(),
                'confirmed',
            ],
        ], [
            // Mensagens traduzidas e explicativas em PT-BR
            'current_password.required'         => 'Informe a sua senha atual para continuar.',
            'current_password.current_password' => 'A senha atual informada está incorreta.',
            'password.required'                 => 'Informe a nova senha.',
            'password.different'                => 'A nova senha não pode ser igual à senha atual.',
            'password.min'                      => 'A nova senha precisa ter no mínimo 8 caracteres.',
            'password.numbers'                  => 'A nova senha precisa conter pelo menos um número.',
            'password.symbols'                  => 'A nova senha precisa conter pelo menos um caractere especial (!@#$...).',
            'password.confirmed'                => 'A confirmação de senha não coincide com a nova senha.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
