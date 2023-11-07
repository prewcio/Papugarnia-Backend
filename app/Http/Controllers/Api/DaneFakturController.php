<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DaneFaktur;
use Illuminate\Http\Request;

class DaneFakturController extends Controller
{
    function saveDane(Request $request){
        $firmName = $request->input('firmName');
        $street = $request->input('street');
        $buildingNumber = $request->input('buildingNumber');
        $apartmentNumber = $request->input('apartmentNumber');
        $postalCode = $request->input('postalCode');
        $city = $request->input('city');
        $nippesel = $request->input('nippesel');
        $email = $request->input('email');
        $phoneNumber = $request->input('phoneNumber');
        $receiptNumber = $request->input('receiptNumber');
        $date = $request->input('date');
        $price = $request->input('price');
        $time = strtotime($date);
        $newFormat = date('Y-m-d', $time);
        $uzup = $request->input('uzupelnione');

        $dane = new DaneFaktur();
        $dane->firmName = $firmName;
        $dane->street = $street;
        $dane->buildingNumber = $buildingNumber;
        $dane->apartmentNumber = $apartmentNumber;
        $dane->postalCode = $postalCode;
        $dane->city = $city;
        $dane->NIPpesel = $nippesel;
        $dane->email = $email;
        $dane->phoneNumber = $phoneNumber;
        $dane->receiptNumber = $receiptNumber;
        $dane->price = $price;
        $dane->date = $newFormat;
        $dane->uzup = $uzup;
        $dane->wystawione = false;
        $dane->save();
        return response()->json([
            'success' => 1
        ]);
    }
}
