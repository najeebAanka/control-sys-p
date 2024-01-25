<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Localizer
{
    
    
    public function handle(Request $request, Closure $next)
    {
        
        
     // Check header request and determine localizaton
     $local = ($request->hasHeader('Accept-Language')) ? $request->header('Accept-Language') : 'en';
     // set laravel localization
       app()->setLocale($local);
        
        return $next($request);
    }
}
