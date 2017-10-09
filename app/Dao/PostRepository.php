<?php
/**
 * Created by PhpStorm.
 * User: cubas
 * Date: 04/10/17
 * Time: 20:49
 */

namespace App\Dao;


use App\Models\Postagem;

class PostRepository
{

    public function save($post)
    {
        $post->save();
    }

    public function delete($post)
    {
        $post->delete();
    }

    public function findall()
    {
        return Postagem::with('comentarios')->get();
    }

    public function find($id)
    {
        return Postagem::with('comentarios')->find($id);
    }

}