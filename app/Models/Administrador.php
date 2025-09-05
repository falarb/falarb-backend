<?php

namespace App\Models;

use App\Traits\HasAlphanumericId;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Administrador extends Authenticatable
{
    use HasAlphanumericId, HasApiTokens;

    protected $table = 'administradors';

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'telefone',
        'ativo',
        'ultimo_acesso',
        'desativado_em',
    ];

    protected $hidden = [
        'senha',
    ];

    // Relacionamentos
    public function modificacoes()
    {
        return $this->hasMany(Modificacao::class, 'id_user');
    }

    // Mutator para hash da senha
    public function setSenhaAttribute($value)
    {
        $this->attributes['senha'] = Hash::make($value);
    }

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function getAuthIdentifierName()
    {
        return 'email';
    }
}
