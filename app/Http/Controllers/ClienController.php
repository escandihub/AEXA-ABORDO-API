<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\API\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        return response()->json([
            "user" => $user,
            "token" => $user->createToken('omnibus', ['*'], now()->addDay())->plainTextToken
        ], 200);

    }
}
