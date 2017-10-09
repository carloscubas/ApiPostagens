<?php
/**
 * Created by PhpStorm.
 * User: cubas
 * Date: 04/10/17
 * Time: 20:56
 */

namespace App\Dao;

use App\Models\Notificacao;
use Illuminate\Support\Facades\DB;

class NotificacaoRepository
{

    public function save($notificacao)
    {
        $notificacao->save();
    }

    public function delete($notificacao)
    {
        $notificacao->delete();
    }

    public function findall($idusuario)
    {
        $sql = "
            select 
                n.id,
                n.data, 
                u.name as usuario, 
                p.titulo as postagem, 
                c.conteudo as comentario 
            from 
                notificacoes n, 
                users u, 
                postagens p, 
                comentarios c 
            where 
                n.user_id = u.id and 
                n.postagem_id = p.id and 
                n.comentario_id = c.id and
                (n.data_expiracao is null or data_expiracao <= now()) and
                p.user_id = $idusuario";


            if( Cache::has( 'index_notification_'.md5($sql) ) ) {
                $result = Cache::get( 'index_notification_'.md5($sql) );
            }else{
                $result =  DB::select($sql);
                Cache::put( 'index_notification_'.md5($sql), $result, 5 );
            }

            return $result;
    }

    public function find($id)
    {
        return Notificacao::with('postagem')->find($id);
    }




}