<?php
/**
 * Created by PhpStorm.
 * User: cubas
 * Date: 08/10/17
 * Time: 18:21
 */

namespace App\Http\Controllers;

use App\Dao\NotificacaoRepository;
use App\Models\Notificacao;
use Illuminate\Http\Request;
use JWTAuth;


class NotificacaoController extends Controller
{
    private $notificationRepository;

    public function __construct() {
        $this->middleware('autenticador-middleware');
        $this->notificationRepository = new NotificacaoRepository();
    }

    public function index(Request $request)
    {
        $input = $request->all();
        $user = JWTAuth::toUser($input['token']);

        if( Cache::has( 'index_notification_'.$user->id ) ) {
            $transacao = Cache::get( 'index_notification_'.$user->id );
        }else{
            $notifications = $this->notificationRepository->findall($user->id);
            Cache::put( 'index_notification_'.$user->id, $notifications, 5 );
        }

        return response()->json($notifications, 201);
    }

    public function show($id, Request $request)
    {
        $input = $request->all();
        $user = JWTAuth::toUser($input['token']);

        $notification = $this->notificationRepository->find($id);

        if($notification->postagem->user_id == $user->id){

            if($notification->data_expiracao == null){
                $dataexpiracao_postagem = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")) + 3600 );
                $notification->data_expiracao = $dataexpiracao_postagem;
                $this->notificationRepository->save($notification);
                return response()->json($notification, 201);
            }

        }else{

            return response()->json([
                'message'   => 'Unauthorized',
            ], 401);

        }

        return $notification;
    }

}