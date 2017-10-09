<?php
/**
 * Created by PhpStorm.
 * User: cubas
 * Date: 04/10/17
 * Time: 20:56
 */

namespace App\Dao;


use Illuminate\Support\Facades\DB;

class TransacaoRepository
{

    public function save($transacao)
    {
        $transacao->save();
    }

    public function delete($transacao)
    {
        $transacao->delete();
    }

    public function findall()
    {
        return Transacao::all();
    }

    public function find($id)
    {
        return Transacao::with('postagens')->find($id);
    }

    public function getSaldo($id){

        $result = DB::select("select sum(valor) as saldo from transacoes where user_id = " . $id);

        return $result[0]->saldo;

    }


}