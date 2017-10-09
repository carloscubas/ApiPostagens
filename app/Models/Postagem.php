<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postagem extends Model
{
    protected $fillable = ['titulo', 'conteudo', 'data_postagem'];

    protected $table = 'postagens';

    public function comentarios()
    {
        return $this->hasMany('App\Models\Comentario');
    }

    /*
    public function usuario()
    {
        return $this->ha
    }
    */

    public function usuario()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}