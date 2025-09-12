<?php

namespace App\Models;

use App\Traits\HasAlphanumericId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modificacao extends Model
{
    use HasAlphanumericId;

    protected $table = 'modificacoes';

    protected $fillable = [
        'valor_novo',
        'valor_anterior',
        'gerado_em',
        'tipo',
        'id_administrador',
        'id_solicitacao',
        'id_categoria',
        'id_comunidade',
        'id_cidadao'
    ];

    protected $casts = [
        'gerado_em' => 'datetime',
    ];

    // Relacionamentos
    public function administrador()
    {
        return $this->belongsTo(Administrador::class, 'id_administrador');
    }

    public function solicitacao()
    {
        return $this->belongsTo(Solicitacao::class, 'id_solicitacao');
    }
}
