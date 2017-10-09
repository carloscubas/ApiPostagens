<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{

    protected $fillable = ['user_id', 'postagem_id', 'comentario_id', 'data', 'data_expiracao'];

    protected $table = 'notificacoes';

    public function postagem()
    {
        return $this->belongsTo('App\Models\Postagem', 'postagem_id');
    }

}


