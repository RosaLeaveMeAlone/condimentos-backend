<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
            $cartToken = $request->header('X-Cart-Token');
    
            $cart = null;

            if ($cartToken) {
                $cart = Cart::firstWhere('token', $cartToken);
            }
    
            $request->merge(['cart' => $cart]);
    
            return $next($request);
    }
}
