<?php

namespace App\Http\Middleware;

use Closure;

class SignatureMiddleware
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
        $data = $request->all();
        if(array_key_exists('service_type', $data)) {
            setServiceType($data['service_type']);
            setServiceId($data['service_id']);
        }
        return $next($request);
    }
}
