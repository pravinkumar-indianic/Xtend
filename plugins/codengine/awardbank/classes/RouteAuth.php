<?php namespace Codengine\Awardbank\Classes;

use Auth;
use Closure;
use Response;

class RouteAuth 
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
        	
            return Response::make('Forbidden', 403);
        }

        return $next($request);
    }
}
