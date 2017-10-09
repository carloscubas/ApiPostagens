<?php

namespace App\Http\Controllers;

use App\Dao\NotificacaoRepository;
use App\Dao\PostRepository;
use App\Models\Comentario;
use App\Dao\TransacaoRepository;
use App\Services\ComentarioService;
use Illuminate\Http\Request;
use App\Dao\CommentsRepository;
use JWTAuth;

class ComentsController extends Controller
{

    private $commentsRepository;
    private $transacaoRepository;
    private $postagemRepository;
    private $comentarioService;

    public function __construct() {
        $this->middleware('autenticador-middleware', ['except' => ['index', 'show']]);
        $this->commentsRepository = new CommentsRepository();
        $this->transacaoRepository = new TransacaoRepository();
        $this->postagemRepository = new PostRepository();
        $this->comentarioService = new ComentarioService();
    }

    public function index(Request $request)
    {
        $input = $request->all();

        if($request->has('limit')){
            $limit = $input['limit'];
        }else{
            $limit = 0;
        }

        if($request->has('offset')){
            $offset = $input['offset'];
        }else{
            $offset = 0;
        }

        if($request->has('idpost')){
            $idpost = $input['idpost'];
        }else{
            $idpost = 0;
        }

        $comentarios = $this->commentsRepository->findall($limit, $offset, $idpost);
        return response()->json($comentarios);
    }

    public function show($id)
    {

        $comentario = $this->commentsRepository->find($id);


        if(!$comentario) {
            return response()->json([
                'message'   => 'Record not found',
            ], 404);
        }

        return response()->json($comentario);

    }

    public function store(Request $request)
    {

        $publicar = false;
        $message = "";

        $input = $request->all();

        $today = date("Y-m-d H:i:s");

        $user = JWTAuth::toUser($input['token']);

        $total = $this->commentsRepository->checkTotalComentsSeconds($user->id);

        if ($total <= 2 ) {

            $postagem = $this->postagemRepository->find($request->all()["postagem_id "]);

            $comentario = new Comentario();
            $comentario->fill($request->all());
            $comentario->data_comentario = $today;
            $comentario->postagem_id = $postagem->id;
            $comentario->user_id = $user->id;
            $comentario->valor = $comentario->valor != null ? $comentario->valor : 0.0;

            /*
            echo '<pre>';
            var_dump($comentario);
            echo '</pre>';
            exit;
            */

            // se o usuario estiver comprando destaque
            if ($comentario->valor > 0) {

                //verifica se ele tem saldo para isso
                $saldo = $this->transacaoRepository->getSaldo($user->id);

                if ($comentario->valor <= $saldo) {
                    $publicar = true;
                    $this->comentarioService->processaComentario($comentario);

                } else {
                    $message = 'Unauthorized - Saldo insuficiente';
                }

            } else {

                if ($user->assinante == 'S' and $postagem->usuario->assinante == 'S') {
                    $publicar = true;
                    $this->comentarioService->processaComentario($comentario);

                } elseif ($user->assinante == 'S' and $postagem->usuario->assinante == 'N') {

                    $publicar = true;
                    $this->comentarioService->processaComentario($comentario);

                } else {

                    $message = 'Unauthorized - Usuário não assinante não pode comentar em post de assinante';

                }

            }


        }else{
            $message = 'Unauthorized - Usuário não pode comentar mais de 2 comentários a cada 60 segundos';
        }

        if($publicar) {
            return response()->json($comentario, 201);

        }else{

            return response()->json([
                'message' => $message,
            ], 401);
        }

    }



    public function destroy(Request $request)
    {

        $input = $request->all();

        if($input['token'] != null){

            $user = JWTAuth::toUser($input['token']);

            $comentario = Comentario::find($input['id']);

            if($comentario->user_id == $user->id || $comentario->postagem_id == $user->id){
                $comentario->delete();
                return response()->json([
                    'message'   => 'Ok',
                ], 201);
            }else{
                return response()->json([
                    'message'   => 'Unauthorized',
                ], 401);
            }

        }else{

            return response()->json([
                'message'   => 'Bad Request - Token invalid',
            ], 400);

        }

    }


}