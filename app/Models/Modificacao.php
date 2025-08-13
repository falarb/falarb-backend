<?php

namespace App\Models;

use App\Traits\HasAlphanumericId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modificacao extends Model
{
    use HasAlphanumericId, SoftDeletes;
    
    protected $table = 'modificacoes';

    protected $fillable = [
        'valor_novo',
        'valor_anterior',
        'gerado_em',
        'tipo',
        'id_user',
        'id_solicitacao'
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
