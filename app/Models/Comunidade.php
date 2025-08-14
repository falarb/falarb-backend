<?php

namespace App\Models;

use App\Traits\HasAlphanumericId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comunidade extends Model
{
    use HasAlphanumericId, SoftDeletes;

    protected $table = 'comunidades';

    protected $fillable = ['nome'];

    // Relacionamentos
    public function cidadaos()
    {
        return $this->hasMany(Cidadao::class, 'id_comunidade');
    }

    public function solicitacoes()
    {
        return $this->hasMany(Solicitacao::class, 'id_comunidade');
    }
}
