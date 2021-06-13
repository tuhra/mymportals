<?php

namespace App\Http\Middleware;

use Closure;
use Session;

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
        // if(Session::has('service_type') && Session::has('service_id')) {
        //     return $next($request); 
        // }

        if(array_key_exists('service_type', $data) && array_key_exists('service_id', $data)) {
            setServiceType($data['service_type']);
            setServiceId($data['service_id']);
            return $next($request);
        }
        return redirect('services');
    }
}
