<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\JsonResponse;

class CursoResource extends JsonResource
{
    /**
     * Transforma o recurso em um array.
     */
    public function toArray(Request $request): array
    {
        $usuario = $request->user(); // Identifica o usuário logado

        return [
            'id_curso'      => $this->id_curso,
            'titulo'        => $this->titulo,
            'descricao'     => $this->descricao,
            'categoria'     => $this->categoria,
            'carga_horaria' => $this->carga_horaria,
            'imagem'        => $this->imagem ? asset('storage/' . $this->imagem) : null,
            'status'        => $this->status,
            'total_aulas'   => $this->total_aulas,
            
            // Injeta o cálculo do progresso dinâmico do usuário autenticado
            'progresso'     => $usuario ? $usuario->progressoNoCurso($this->id_curso) : 0.00,

            // Carrega os módulos e suas respectivas aulas somente se foram carregados pelo with()
            'modulos'       => $this->relationLoaded('modulos') ? $this->modulos->map(function ($modulo) use ($usuario) {
                return [
                    'id_modulo' => $modulo->id_modulo,
                    'titulo'    => $modulo->titulo,
                    'ordem'     => $modulo->ordem,
                    'aulas'     => $modulo->aulas->map(function ($aula) use ($usuario) {
                        return [
                            'id_aula'      => $aula->id_aula,
                            'titulo'       => $aula->titulo,
                            'url_video'    => $aula->url_video,
                            'duracao'      => $aula->duracao,
                            'ordem'        => $aula->ordem,
                            
                            // Verifica especificamente se o usuário logado concluiu esta aula individual
                            'concluida'    => $usuario ? $usuario->aulasConcluidas()->where('progresso.id_aula', $aula->id_aula)->exists() : false,
                        ];
                    }),
                ];
            }) : [],

            'criado_em'     => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}

class CursoController extends Controller
{
    /**
     * Retorna o catálogo de cursos ativos com filtros opcionais.
     */
    public function index(Request $request): JsonResponse
    {
        // Iniciamos a query apenas com cursos ativos
        $query = Curso::where('status', 'ativo');

        // 1. Filtro por Categoria (Área) - Ex: ?categoria=Tecnologia
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // 2. Filtro de Busca por Texto (Título/Descrição) - Ex: ?busca=laravel
        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(function ($q) use ($busca) {
                $q->where('titulo', 'like', "%{$busca}%")
                  ->orWhere('descricao', 'like', "%{$busca}%");
            });
        }

        // 3. Paginação para evitar sobrecarregar o app do aluno com milhares de registros de uma vez
        $cursos = $query->latest()->paginate(15);

        // Retorna usando o Resource formatador da API (seus arquivos de resposta da pasta Http)
        // Usamos o 'app/Http/Resources/CursoResource' aqui para mapear os campos
        return response()->json([
            'sucesso' => true,
            'dados' => \App\Http\Resources\CursoResource::collection($cursos)->response()->getData(true)
        ]);
    }

    /**
     * Retorna os detalhes de um único curso com seus módulos e aulas.
     */
    public function show($id): JsonResponse
    {
        // Busca o curso ativo com seus módulos e respectivas aulas ordenadas
        $curso = Curso::where('status', 'ativo')
            ->with(['modulos' => function ($query) {
                $query->orderBy('ordem') // Se tiver coluna de ordenação
                      ->with(['aulas' => function ($query) {
                          $query->orderBy('ordem');
                      }]);
            }])
            ->find($id);

        if (!$curso) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Curso não encontrado ou indisponível.'
            ], 404);
        }

        return response()->json([
            'sucesso' => true,
            'dados' => new \App\Http\Resources\CursoResource($curso)
        ]);
    }
}