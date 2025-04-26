<?php

namespace App\Http\Controllers;

use App\Models\API\Printer;
use Illuminate\Http\Request;
use App\Models\API\Device;
use Illuminate\Support\Facades\Auth;
use App\Models\API\DeviceLocation;

class DevicePrinterController extends Controller
{
    public function CreateOrCheck(Request $request) {}
    /**
     * Se asigna la impresora a la tablet
     */
    public function asignacion(Request $request)
    {

        $usuario = Auth()->user();

        $impresora = Printer::where("blue_uuid", $request->blue_uuid)->first();
        $device = DeviceLocation::where('user_id', $usuario->id_usuario)->first();
        $dispositivo = $device->device;
        // si no existe la impresora
        if (!$impresora) {
            $printer =  Printer::firstOrCreate([
                "modelo" => $request->name,
                "characteristic_uuid" => $request->characteristic_uuid,
                "service_uuid" => $request->service_uuid,
                "blue_uuid" => $request->blue_uuid,
            ]);
            $dispositivo->associatePrinter($printer, true);
        } else {
            $dispositivo->associatePrinter($impresora, true);
        }
        return response()->json([
            "message" => "se ha guardado la impresora con exito."
        ], 200);
        // VERIFICAR DISPOSITIVO VINCULADO

    }
    /**
     * Se optiene datos de la impresora
     * si el dispositivo ya cuenta con una 
     * asignacion
     */
    public function getPrinter(Request $request)
    {
        $usuario = Auth()->user();

        $device = DeviceLocation::where('user_id', $usuario->id_usuario)->first();
        $dispositivo = $device->device;

        if($dispositivo->printers()->count() === 0) {
            return response()->json(['message' => 'no hay impresora vinculada'], 404);
        }

        return response()->json(
            $dispositivo->printers()->wherePivot('is_default', true)->first(),
            200
        );
    }
}
