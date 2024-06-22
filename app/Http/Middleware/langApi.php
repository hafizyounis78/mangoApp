<?php

namespace App\Http\Middleware;

use Closure;
use App;

class langApi
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
        $lang = $request->headers;
     //   dd($lang);
        if($lang != 'en' && $lang != 'ar') {
            $lang = 'en';
        }
		App::setLocale($lang);
        return $next($request);
    }
}
