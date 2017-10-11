<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Produto;
use App\Dao\ProdutoRepository;
use Illuminate\Http\Request;
use JWTAuth;

class ProdutoController extends Controller
{

    public function __construct() {
        $this->middleware('autenticador-middleware');
    }

    public function index()
    {

        $produtos = Produto::with('categorias')
            ->orderBy('nome')
            ->get();

        return $produtos;

    }


}
