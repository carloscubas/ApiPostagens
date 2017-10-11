<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produtos';

    protected $fillable = ['nome', 'descricao'];

    public function categorias() {
        return $this->belongsToMany('App\Categoria', 'produto_categoria', 'produto_id', 'categoria_id');
    }
}
