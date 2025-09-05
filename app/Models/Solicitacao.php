<?php

namespace App\Models;

use App\Traits\HasAlphanumericId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Solicitacao extends Model
{
    use HasAlphanumericId;

    protected $table = 'solicitacoes';

    protected $fillable = [
        'status',
        'mot_indeferimento',
        'descricao',
        'data_agendamento',
        'data_conclusao',
        'latitude',
        'longitude',
        'token_solicitacao',
        'id_cidadao',
        'id_categoria',
        'id_comunidade',
    ];

    protected $casts = [
        'data_agendamento' => 'date',
        'data_conclusao' => 'datetime',
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
    ];

    // Relacionamentos
    public function cidadao()
    {
        return $this->belongsTo(Cidadao::class, 'id_cidadao');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function comunidade()
    {
        return $this->belongsTo(Comunidade::class, 'id_comunidade');
    }

    public function modificacoes()
    {
        return $this->hasMany(Modificacao::class, 'id_solicitacao');
    }
}
