<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\API\Device;
use App\Models\trakingApp;

class validateUpdate
{
    /**
     * Handle an incoming request.
     * se encarga de validar si se encuentra una actulizacion de la aplicacion de abordo
     * + se agrega un valor a objeto de response 
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tracking = trakingApp::where('active', 1)->first();
        if($request->app['versionName'] == $tracking->versionName){
            return $next($request->merge(['upgradeable' => false]));
        }
        return $next($request->merge(['upgradeable' => [
            'upgradeable' => true,
            'url' => $tracking->path_app,
            'nombre' => $tracking->nombre,
            'versionName' => $tracking->versionName
        ]]));
    }
}
