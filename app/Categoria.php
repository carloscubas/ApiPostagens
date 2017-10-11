<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = ['id', 'nome'];

    public function produtos() {
        return $this->belongsToMany('App\Produto', 'produto_categoria', 'produto_id', 'categoria_id');
    }

}
