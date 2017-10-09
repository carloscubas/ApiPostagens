<?php
/**
 * Created by PhpStorm.
 * User: cubas
 * Date: 04/10/17
 * Time: 20:05
 */

namespace App\Services;


use App\Dao\CommentsRepository;
use App\Dao\NotificacaoRepository;
use App\Dao\PostRepository;
use App\Dao\TransacaoRepository;
use App\Models\Transacao;
use App\Models\Notificacao;
use App\User;
use Illuminate\Support\Facades\Mail;
use JWTAuth;

class ComentarioService
{

    private $transacaoRepository;
    private $notificacaoRepository;
    private $postagemRepository;

    public function __construct() {
        $this->commentsRepository = new CommentsRepository();
        $this->transacaoRepository = new TransacaoRepository();
        $this->notificacaoRepository = new NotificacaoRepository();
        $this->postagemRepository = new PostRepository();
    }

    public function processaComentario($comentario){

        $user = User::all()->get($comentario->user_id);

        // salva comentario
        if($comentario->valor > 0){
            $dataDestaque = date("Y-m-d H:i:s", strtotime($comentario->data_comentario) + (round($comentario->valor, 0) * 60) );
            $comentario->data_destaque = $dataDestaque;

            // salva histórico de transação
            $transacao = new Transacao();
            $transacao->data = $comentario->data_comentario;
            $transacao->valor = -1 * abs($comentario->valor);
            $transacao->tipo = 'D';
            $transacao->user_id = $comentario->user_id;
            $this->transacaoRepository->save($transacao);

        }

        $this->commentsRepository->save($comentario);

        // notifica sistema
        $notificacao = new Notificacao();
        $notificacao->user_id = $comentario->user_id;
        $notificacao->postagem_id = $comentario->postagem_id;
        $notificacao->data = $comentario->data_comentario;
        $notificacao->comentario_id = $comentario->id;
        $this->notificacaoRepository->save($notificacao);

        /* notifica o dono do post */
        /*
        Mail::send('emails.posts.notification', ['user' => "carlos.cubas@gmail.com"], function ($m) use ($user) {
            $m->from('hello@app.com', 'Your Application');

            $m->to($user->email, $user->name)->subject('Your Reminder!');
        });
        */

    }

}