<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcionario;
use App\Models\Pedido;
/**
 * Tela Inicial do Admin
 */
class DashboardController extends Controller {
    private $dados = ['menu' => 'dashboard'];

    /** Tela Inicial do Dashboard */
    public function index() {
        $this->dados['funcionariosCadastrados'] = Funcionario::count();
        $this->dados['pedidosCadastrados'] = Pedido::count();
        $pedido = new Pedido();
        $this->dados['pedidosAtivos'] = $pedido->contarPedidosAtivos();
        return view('dashboard.index', $this->dados);
    }
}
