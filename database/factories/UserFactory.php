<?php

namespace Database\Factories;

use App\Models\Usuario; // 👈 Mude de User para Usuario
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Usuario>
 */
class UserFactory extends Factory
{
    protected $model = Usuario::class; // 👈 Mapeia para a model customizada

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            //'email_verified_at' => now(),
            // Garanta que usa o atributo 'password' criptografado via Hash::make
            'password' => static::$password ??= Hash::make('password'),
            'perfil' => 'colaborador',
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}