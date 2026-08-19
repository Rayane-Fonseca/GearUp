<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable implements FilamentUser, HasName
{
    use HasApiTokens, HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdministrador();
    }

    public function getFilamentName(): string
    {
        return $this->nome;
    }

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nome',
        'email',
        'password',
        'cpf',
        'perfil',
        'cargo',
        'area',
        'foto',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getRouteKeyName()
    {
        return 'id_usuario';
    }

    public function isAdministrador(): bool
    {
        return $this->perfil === 'administrador';
    }

    public function isColaborador(): bool
    {
        return $this->perfil === 'colaborador';
    }

    public function iniciais(): string
    {
        $partes = preg_split('/\s+/', trim($this->nome));
        $iniciais = strtoupper(substr($partes[0] ?? 'U', 0, 1) . substr($partes[count($partes) - 1] ?? '', 0, 1));

        return $iniciais ?: 'U';
    }

    public function progressos(): HasMany
    {
        return $this->hasMany(Progresso::class, 'usuario_id', 'id_usuario');
    }

    public function certificados(): HasMany
    {
        return $this->hasMany(Certificado::class, 'id_usuario', 'id_usuario');
    }

    public function notificacoes(): HasMany
    {
        return $this->hasMany(Notificacao::class, 'usuario_id', 'id_usuario');
    }
}
