<?php

namespace App\Models;

use App\Traits\HasAlphanumericId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cidadao extends Model
{
    use HasAlphanumericId;

    protected $table = 'cidadaos';

    protected $fillable = [
        'nome',
        'email',
        'email_verificado',
        'telefone',
        'cpf',
        'ultimo_codigo',
        'codigo_enviado_em',
        'bloqueado',
        'ativo',
    ];

    protected $casts = [
        'email_verificado' => 'boolean',
        'bloqueado' => 'boolean',
        'codigo_enviado_em' => 'datetime',
        'ativo' => 'boolean',
    ];

    // Relacionamentos
    public function solicitacoes()
    {
        return $this->hasMany(Solicitacao::class, 'id_cidadao');
    }
}
