<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\API\Device;
use App\Models\devicesTraking;


class CheckDevice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info($request->all());
        if(array_key_exists('device', $request->all())){
            $exist = Device::where('identifier', $request->device['identifier'])->first();

            if(!$exist){
                $device = Device::updateOrCreate($request->device);
                $traking = $device->trakingDevice()->create($request->app);
                $device->location()->create(['latitud' => $request->lat, 'longitud' => $request->log]);
            }else{
                $exist->location()->create(['latitud' => $request->lat, 'longitud' => $request->log]);
            }
           
              
            // devicesTraking::updateOrCreate($request->app);
            
        }

        return $next($request);
    }

   
}
