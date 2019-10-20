<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;
use App\Model\Sistema\Perfil;

class Autorizado
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
        return response()->json(['mensagem' => 'Acesso negado. Você não está autorizado acessar esse recurso'], 401);
        if (auth()->check() || !Perfil::autorizado($funcionalidade, Auth::user()->id )) {
            return response()->json(['mensagem' => 'Acesso negado. Você não está autorizado acessar esse recurso'], 403);
        }

        return $next($request);
    }
}
