<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{

    protected $fillable = ['titulo', 'conteudo', 'data_comentario', 'data_destaque','user_id', "postagem_id", 'valor'];

    protected $table = 'comentarios';

    public function postagens()
    {
        return $this->hasMany('App\Model\Postagem');
    }

}