<?php

namespace App\Models;

use App\Traits\HasAlphanumericId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use HasAlphanumericId;

    protected $table = 'categorias';

    protected $fillable = [
        'nome',
        'ativo',
    ];

    // Relacionamentos
    public function solicitacoes()
    {
        return $this->hasMany(Solicitacao::class, 'id_categoria');
    }
}
