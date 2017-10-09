<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{

    protected $fillable = ['tipo', 'valor', 'data', 'user_id'];

    protected $table = 'transacoes';

}


