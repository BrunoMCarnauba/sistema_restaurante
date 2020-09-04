<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;

/**
 * Classe responsável por controlar rotas autenticadas.
 * Só permite o acesso as rotas que estão agrupadas nesse Middleware, se passar o token válido que foi gerado no momento do login
 */
class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $jwt = $request->header('Authorization');   //Normalmente o token é enviado no header da requisição com o nome Authorization.
            $dados = JWT::decode($jwt, config('jwt.senha'), ['HS256']); //Verifica se o token é válido. Se não for, cai no catch. - "$jwt" são os dados salvos no token, "config('jwt.senha')" é a senha usada para assinar os dados do token e ['HS256'] é o algoritmo padrão usado para gerar a assinatura com a senha
            return $next($request); //Deixa acessar a rota que foi requisitada
        } catch (\Exception $e) { 
            //Adicionar \ antes no Exception, porque estamos no namespace App\Http\Middleware
            //Do contrário ele vai procura Exception nesse caminho. 
            return response()->json(['erro' => 'Token inválido'], 403);
        }
    }
}
