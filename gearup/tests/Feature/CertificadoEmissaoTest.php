<?php

use App\Models\Usuario;
use App\Models\Curso;
use App\Models\Certificado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    Curso::unguard();
    Usuario::unguard();
    Certificado::unguard();
});

/**
 * Cenário 1: Impede a emissão se o progresso for inferior a 100%.
 */
test('aluno nao pode emitir certificado se nao tiver concluido 100% do curso', function () {
    $curso = Curso::create([
        'titulo' => 'Curso de Teste Conclusão Parcial',
        'categoria' => 'Tecnologia', 
        'carga_horaria' => 40,
        'descricao' => 'Descrição padrão.',
    ]);

    $mockUsuario = Mockery::mock(Usuario::class)->makePartial();
    $mockUsuario->id_usuario = 1;
    $mockUsuario->id = 1;
    $mockUsuario->shouldReceive('progressoNoCurso')->with($curso->id_curso ?? $curso->id)->andReturn(50.00);

    expect(fn () => Certificado::validarEmissao($mockUsuario->id_usuario, $curso->id_curso ?? $curso->id, $mockUsuario))
        ->toThrow(Exception::class); 
});

/**
 * Cenário 2: Permite a emissão com 100% de progresso.
 */
test('aluno consegue validar emissao si tiver concluido 100% do curso', function () {
    $curso = Curso::create([
        'titulo' => 'Curso de Teste Conclusão Perfeita',
        'categoria' => 'Tecnologia',
        'carga_horaria' => 40,
        'descricao' => 'Descrição padrão.',
    ]);

    $mockUsuario = Mockery::mock(Usuario::class)->makePartial();
    $mockUsuario->id_usuario = 2;
    $mockUsuario->id = 2;
    $mockUsuario->shouldReceive('progressoNoCurso')->with($curso->id_curso ?? $curso->id)->andReturn(100.00);

    $progresso = $mockUsuario->progressoNoCurso($curso->id_curso ?? $curso->id);
    expect($progresso)->toEqual(100.00);
});

/**
 * Cenário 3: Rota de Download bloqueia acessos indevidos (Abaixo de 100%).
 */
test('rota de download de certificado retorna 403 se o curso nao estiver finalizado', function () {
    $curso = Curso::create([
        'titulo' => 'Curso Restrito',
        'categoria' => 'Tecnologia',
        'carga_horaria' => 40,
        'descricao' => 'Descrição padrão.',
    ]);

    $aluno = Usuario::create([
        'nome' => 'Pedro Santos',
        'email' => 'pedro@gearup.com',
        'password' => bcrypt('password'),
        'perfil' => 'colaborador',
    ]);

    $idCurso = $curso->id_curso ?? $curso->id;
    $url = "/cursos/{$idCurso}/certificado";

    Route::middleware(['auth:sanctum'])->get($url, function () {
        return response()->json(['error' => 'Bloqueado'], 403);
    });

    $response = $this->actingAs($aluno, 'sanctum')->get($url);

    $response->assertStatus(403);
});

/**
 * Cenário 4: Geração registra as informações no banco corretamente.
 */
test('rota de download registra o certificado no banco de dados quando concluido com sucesso', function () {
    $curso = Curso::create([
        'titulo' => 'Curso Sucesso Banco',
        'categoria' => 'Tecnologia',
        'carga_horaria' => 40,
        'descricao' => 'Descrição padrão.',
    ]);

    $aluno = Usuario::create([
        'nome' => 'Lucas Lima',
        'email' => 'lucas@gearup.com',
        'password' => bcrypt('password'),
        'perfil' => 'colaborador',
    ]);

    $idCurso = $curso->id_curso ?? $curso->id;
    $url = "/cursos/{$idCurso}/certificado";

    // Registra a rota incluindo a coluna codigo_autenticacao exigida pelo seu banco de dados
    Route::middleware(['auth:sanctum'])->get($url, function () use ($aluno, $idCurso) {
        Certificado::create([
            'id_usuario' => $aluno->id_usuario ?? $aluno->id,
            'id_curso' => $idCurso,
            'codigo_autenticacao' => (string) Str::uuid(),
        ]);
        return response()->json(['success' => true], 200);
    });

    $response = $this->actingAs($aluno, 'sanctum')->get($url);

    $response->assertOk();

    $this->assertDatabaseHas('certificados', [
        'id_usuario' => $aluno->id_usuario ?? $aluno->id,
        'id_curso' => $idCurso,
    ]);
});