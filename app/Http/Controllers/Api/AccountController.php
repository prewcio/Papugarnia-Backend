<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class AccountController extends Controller
{
    public function updatePassword(Request $request)
    {
        # Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ]);

        $errorPass = [
            "old_password" => "Błędne aktualne hasło."
        ];
        $errorPassSame = [
            "new_password" => "Nowe hasło nie może być takie samo."
        ];

        if(Hash::check($request->new_password, auth()->user()->password)){
            return response()->json([
                "message" => "Błędne aktualne hasło.",
                "errors" => $errorPassSame
            ], 422);
        }

        #Match The Old Password
        if(!Hash::check($request->old_password, auth()->user()->password)){
            return response()->json([
                "message" => "Błędne aktualne hasło.",
                "errors" => $errorPass
            ], 422);
        }


        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
        $passChanged = [
            "passwordChange" => "Hasło zostało zmienione"
        ];
        return response()->json([
            "message" => "Hasło zostało zmienione",
            "errors" => $passChanged
    ]);
    }
}
