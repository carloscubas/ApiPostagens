<?php

namespace App\Http\Controllers;

use App\Dao\PostRepository;
use App\Models\Postagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use JWTAuth;

class PostController extends Controller
{

    private $postRepository;

    public function __construct() {
        $this->middleware('autenticador-middleware', ['except' => ['index', 'show']]);
        $this->postRepository = new PostRepository();
    }

    public function index()
    {

        if( Cache::has( 'index_posts' ) ) {
            $postagem =  Cache::get( 'index_posts' );
        }else{
            $postagem = $this->postRepository->findall();
            Cache::put( 'index_posts', $postagem, 5 );
        }

        return response()->json($postagem, 201);
    }

    public function show($id)
    {

        $postagem = $this->postRepository->find($id);

        if(!$postagem) {
            return response()->json([
                'message'   => 'Record not found',
            ], 404);
        }

        return response()->json($postagem);
    }


    public function store(Request $request)
    {

        $postagem = new Postagem();
        $postagem->fill($request->all());
        $postagem->user_id = \Request::get('user')->id;

        $this->postRepository->save($postagem);

        return response()->json($postagem, 201);

    }

    public function update(Request $request, $id)
    {

        $postagem = $this->postRepository->find($id);

        if(!$postagem) {
            return response()->json([
                'message'   => 'Record not found',
            ], 404);
        }

        if(\Request::get('user')->id == $postagem->id){
            $postagem->fill($request->all());
            $this->postRepository->save($postagem);

            return response()->json($postagem, 201);
        }else{
            return response()->json([
                'message'   => 'Unauthorized',
            ], 401);
        }

    }

    public function destroy($id)
    {
        $postagem = Postagem::find($id);

        if(!$postagem) {
            return response()->json([
                'message'   => 'Record not found',
            ], 404);
        }else{
            if(\Request::get('user')->id == $postagem->id){

                $this->postRepository->delete($postagem);

            }else{
                return response()->json([
                    'message'   => 'Unauthorized',
                ], 401);
            }
        }
    }
}
