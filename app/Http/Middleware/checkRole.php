<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class checkRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$roles)
    {
       
      // if(empty($roles)) $roles = ['admin'];

      //  foreach($roles as $role) {
        if($request->user()->role === $roles) { 
                return $next($request); 
            //} 
        } 
        return response()->json("unauthorized");
    }
}
