<?php

namespace App\Models;

use App\Traits\HasAlphanumericId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cidadao extends Model
{
    use HasAlphanumericId, SoftDeletes;

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
        'bloqueado_em',
        'bloqueado_por'
    ];

    protected $casts = [
        'email_verificado' => 'boolean',
        'bloqueado' => 'boolean',
        'codigo_enviado_em' => 'datetime',
        'bloqueado_em' => 'datetime',
    ];

    // Relacionamentos
    public function solicitacoes()
    {
        return $this->hasMany(Solicitacao::class, 'id_cidadao');
    }
}
