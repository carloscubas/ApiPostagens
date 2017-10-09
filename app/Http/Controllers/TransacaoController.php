<?php

namespace App\Http\Controllers;

use App\Dao\TransacaoRepository;
use App\Models\Transacao;
use Illuminate\Http\Request;
use JWTAuth;

class TransacaoController extends Controller
{

    private $transactionRepository;

    public function __construct() {
        $this->middleware('autenticador-middleware', ['except' => ['index', 'show']]);
        $this->transactionRepository = new TransacaoRepository()
;    }

    public function index()
    {

        if( Cache::has( 'index_transaction' ) ) {
            $transacao = Cache::get( 'index_transaction' );
        }else{
            $transacao = $this->transactionRepository->findall();
            Cache::put( 'index_transaction', $transacao, 5 );
        }

        return response()->json($transacao);
    }

    public function show($id)
    {

        $transacao = $this->transactionRepository->find($id);

        if(!$transacao) {
            return response()->json([
                'message'   => 'Record not found',
            ], 404);
        }

        return response()->json($transacao);
    }

    public function store(Request $request)
    {

        $transacao = new Transacao();
        $transacao->fill($request->all());
        $transacao->user_id = \Request::get('user')->id;

        $this->transactionRepository->save($transacao);

        return response()->json($transacao, 201);

    }

    public function update(Request $request, $id)
    {

        $transacao = $this->transactionRepository->find($id);

        if(!$transacao) {
            return response()->json([
                'message'   => 'Record not found',
            ], 404);
        }

        $transacao->fill($request->all());
        $this->transactionRepository->save($transacao);

        return response()->json($transacao);

    }

    public function destroy($id)
    {

        $transacao = $this->transactionRepository->find($id);

        if(!$transacao) {
            return response()->json([
                'message'   => 'Record not found',
            ], 404);
        }

        $this->transactionRepository->delete($transacao);
    }

    
}