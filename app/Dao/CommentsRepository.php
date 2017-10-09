<?php
/**
 * Created by PhpStorm.
 * User: cubas
 * Date: 04/10/17
 * Time: 19:25
 */

namespace App\Dao;


use App\Models\Comentario;
use App\Models\Paginacao;
use BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CommentsRepository
{

    public function save($comments)
    {

        $comments->save();
    }

    public function delete($comments)
    {
        $comments->delete();
    }

    public function findall($limit = 0, $offset = 0, $idpost = null)
    {
        $totalComents = $this->getTotalComents($idpost);

        if($limit == 0){
            $limit = $totalComents;
        }

        if($idpost != null){
            $sqlcomplement = " and c.postagem_id =  $idpost ";
        }else{
            $sqlcomplement = "";
        }

        $sql = "
             select * from (
                    select 
                        c.user_id as id_usuario, 
                        c.id as id_postagem, 
                        u.email as login,
                        
                        CASE u.assinante 
                            WHEN 'S' THEN 
                                'S'
                            ELSE 
                                'N'
                        END as assinante,
                        
                        CASE c.valor 
                            WHEN 0 THEN 
                                'N'
                            WHEN null THEN 
                                'N'
                            ELSE 
                                'S'
                        END as destaque,
                        c.data_comentario as data,
                        c.conteudo as comentario,
                        if(data_destaque > now(), data_destaque, data_comentario) as datafinal,
                        c.valor
                    from 
                        comentarios c, 
                        users u 
                    where 
                        c.user_id = u.id " .$sqlcomplement. "
                   ) a order by datafinal desc, valor desc LIMIT $limit OFFSET $offset";


        if( Cache::has( 'index_comments_'.md5($sql) ) ) {
            $paginacao = Cache::get( 'index_comments_'.md5($sql) );
        }else{
            $result =  DB::select($sql);
            $paginacao = new Paginacao();
            $paginacao->total = $totalComents;
            $paginacao->offset = $offset;
            $paginacao->limit = sizeof($result);
            $paginacao->comments = $result;
            Cache::put( 'index_comments_'.md5($sql), $paginacao, 5 );
        }

        return $paginacao;
    }

    public function find($id)
    {
        return Comentario::all()->get($id);
    }

    public function findlastComent($id){

        $comentario = new Comentario();

        $result = DB::select("select * from comentarios where user_id = $id and data_comentario = 
                                (SELECT max(data_comentario) FROM comentarios c where c.user_id = $id)");


        $comentario->id = $result[0]->id;
        $comentario->titulo = $result[0]->titulo;
        $comentario->conteudo = $result[0]->conteudo;
        $comentario->data_comentario = $result[0]->data_comentario;
        $comentario->user_id = $result[0]->user_id;
        $comentario->postagem_id = $result[0]->postagem_id;
        $comentario->valor = $result[0]->valor != null ? $result[0]->valor : 0.0;


        return $comentario;

    }


    public function checkTotalComentsSeconds($id){

        $dateNow = date("Y-m-d H:i:s");
        $dateSubtract = date("Y-m-d H:i:s", strtotime($dateNow) - 10);
        $result = DB::select("select count(*) as total from comentarios where user_id = $id and data_comentario BETWEEN '$dateSubtract' AND '$dateNow'");
        return $result[0]->total;

    }

    public function getTotalComents($idPost = null){

        if($idPost == null){
            $sqlcount = "select count(*) as total from comentarios c,  users u  where  c.user_id = u.id ";
        }else{
            $sqlcount = "select count(*) as total from comentarios c,  users u  where  c.user_id = u.id and postagem_id = " . $idPost;
        }

        $result = DB::select($sqlcount);

        return $result[0]->total;

    }

}