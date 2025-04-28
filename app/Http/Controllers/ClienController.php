<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\API\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\API\Device;

class ClienController extends Controller
{
    public function createToken(Request $request)  {
        $client = Client::where('email', $request->email)->first();
        return response()->json( $client->createToken('omnibus', ['*'], now()->addWeek())->plainTextToken);

    }

    public function register(Request $request)  {

        $password = Hash::make( $request->password );

        $v = Client::create([
            'email' => $request->email,
            'password'=> $password
        ]);
        return response()->json(['status' => 'creado', 'client' => $v ]);
    }

    public function LoginPlainText(Request $request){
        $user = Usuario::where('user', $request->user)->first();
        \Log::info($user);
        $exist = Device::where('identifier', $request->device['identifier'])->first();
        $exist->location()->create(['user_id' => $user->id_usuario, 'latitud' => $request->lat, 'longitud' => $request->log]);
        $expires_in = now()->addHours(12);

        return response()->json([
            "upgradeable" => $request->upgradeable,
            "user" => [
                "nombre" => $user->user,
                "status" => $user->status,
                "terminal" => $user->taquilla->abreviacion
            ],
            "token" => $user->createToken('omnibus', ["*"], $expires_in , $request->lat, $request->log)->plainTextToken,
            "expires_in" => $expires_in
            
        ], 200);

    }
}
