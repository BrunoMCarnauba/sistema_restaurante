<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Firebase\JWT\JWT;

class ApiController extends Controller
{

    /**
     * Quando só puder pegar/alterar dados de um determinado usuário (exemplo: Tarefa que foi cadastrada por usuário de ID 1), 
     * usar esse método para recuperar o ID do usuário no token JWT gerado no momento do login.
     * @param $request | requisição enviada
     * @return int | id do usuário no JWT
     */
    protected function getUsuarioID(Request $request):int {
        $dados = JWT::decode($request->header('Authorization'), config('jwt.senha'), ['HS256']);
        return $dados->id_funcionario;
    }
}
