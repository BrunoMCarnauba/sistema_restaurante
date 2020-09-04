<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        if ($request->session()->has('usuario') && session('usuario')->cargo->tudo_permitido == true)
            return $next($request);
        else 
            return redirect()->route('login')->with('erro', 'Sessão expirada');
    }
}
