<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Nabywcy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    function generateInvoice(Request $request) {
        $netto = [];
        $vat = 0;
        $brutto = 0;
        $array = [];
        $vats = [];

//        Dane
        $dane_faktury = $request->json('dane_faktury');
        $nabywca = $request->json('nabywca');
        $pozycje = $request->json('pozycje');
        $actualVat = 0;
        $index = 1;
        foreach($pozycje as $pozycja){
            if($actualVat != $pozycja['vat']){
                $netto = 0;
                $brutto = 0;
                $vat = 0;
            }
            $netto = round((double)$netto + ((double)$pozycja['ilosc']*(double)$pozycja['kwota'])/(((int)$pozycja['vat']/100)+1),2);
            $brutto = number_format((double)$brutto + ((double)$pozycja['ilosc']*(double)$pozycja['kwota']),2,',','');
            $vat = (double)$brutto - (double)$netto;
            $actualVat = $pozycja['vat'];
            if(!in_array(($pozycja['vat']), $vats)){
                array_push($vats, $pozycja['vat']);
            }
            $array[$pozycja['vat']] = [
                'netto' => $netto,
                'brutto' => $brutto,
                'vat' => $vat,
            ];
            $pozycja['lp'] = $index;
            $index++;
        }
        $fullPrice = 0;
        $fullNetto = 0;
        $fullVat = 0;
        foreach ($vats as $vat){
            $fullPrice = (double)$fullPrice + (double)$array[$vat]['brutto'];
            $fullNetto = (double)$fullNetto + (double)$array[$vat]['netto'];
            $fullVat = (double)$fullVat + (double)$array[$vat]['vat'];
        }
        $text = "19 2490 0005 0000 4530 9737 5421";
        $uwagi = $request->input('uwaga');
        $slowne = $this->numberToWord($fullPrice);
        $nrFaktury = 1;
        $mscFaktury = 1;
        $rokFaktury = 2023;
        if(Invoice::where('miesiac', 1)->where('rok',2023)->exists()) {
            $faktura = Invoice::where('miesiac', 1)->where('rok',2023)->latest()->first();
            $nrFaktury = $faktura->nrFaktury+1;
        }
        $fakturaTitle = "W/".$nrFaktury."/".$mscFaktury."/".$rokFaktury;

        $faktury = new Invoice();
        $faktury->miesiac = $mscFaktury;
        $faktury->rok = $rokFaktury;
        $faktury->nrFaktury = $nrFaktury;
        $faktury->daneFaktury = json_encode($dane_faktury);
        $faktury->nabywca = json_encode($nabywca);
        $faktury->pozycje = json_encode($pozycje);
        $faktury->save();

        $headers = [
            'Content-Type' => 'application/pdf',
        ];

        $pdf = Pdf::loadView('layout', [
            'dane' => $dane_faktury,
            'nazwa' => $fakturaTitle,
            'nabywca' => $nabywca,
            'pozycje' => $pozycje,
            'summary' => $array,
            'vats' => $vats,
            'slowne' => $slowne,
            'fullBrutto' => number_format($fullPrice,2,',',''),
            'fullNetto' => number_format($fullNetto,2,',',''),
            'fullVat' => number_format($fullVat,2,',',''),
            'nrKonta' => $text,
            'uwagi' => $uwagi
        ]);
        $pdf->set_base_path(resource_path('style/Invoice.css'));
        $pdf->save(public_path('faktura-'.$nrFaktury.'-'.$mscFaktury.'-'.$rokFaktury.'.pdf'));
        $file = public_path('faktura-'.$nrFaktury.'-'.$mscFaktury.'-'.$rokFaktury.'.pdf');
        return response()->download($file, 'faktura-'.$nrFaktury.'-'.$mscFaktury.'-'.$rokFaktury.'.pdf', $headers)->deleteFileAfterSend(true);
    }

    function numberToWord($number)
    {
        $ones = array(
            1 => 'jeden',
            2 => 'dwa',
            3 => 'trzy',
            4 => 'cztery',
            5 => 'pięć',
            6 => 'sześć',
            7 => 'siedem',
            8 => 'osiem',
            9 => 'dziewięć'
        );
        $tens = array(
            2 => 'dwadzieścia',
            3 => 'trzydzieści',
            4 => 'czterdzieści',
            5 => 'pięćdziesiąt',
            6 => 'sześćdziesiąt',
            7 => 'siedemdziesiąt',
            8 => 'osiemdziesiąt',
            9 => 'dziewięćdziesiąt'
        );
        $hundreds = array(
            1 => 'sto',
            2 => 'dwieście',
            3 => 'trzysta',
            4 => 'czterysta',
            5 => 'pięćset',
            6 => 'sześćset',
            7 => 'siedemset',
            8 => 'osiemset',
            9 => 'dziewięćset'
        );
        $thousands = array(
            1 => 'tysiąc',
            2 => 'dwa tysiące',
            3 => 'trzy tysiące',
            4 => 'cztery tysiące',
            5 => 'pięć tysięcy',
            6 => 'sześć tysięcy',
            7 => 'siedem tysięcy',
            8 => 'osiem tysięcy',
            9 => 'dziewięć tysięcy'
        );
        if ($number < 1000) {
            if ($number == 10){
                return 'dziesięć';
            }
            if ($number < 10) {
                return $ones[$number];
            } elseif ($number < 100) {
                $tens_digit = intval($number / 10);
                $ones_digit = $number % 10;
                if ($ones_digit > 0) {
                    return $tens[$tens_digit] . ' ' . $ones[$ones_digit];
                } else {
                    return $tens[$tens_digit];
                }
            } else {
                $hundreds_digit = intval($number / 100);
                $tens_and_ones = $number % 100;
                if ($tens_and_ones > 0) {
                    return $hundreds[$hundreds_digit] . ' ' . $this->numberToWord($tens_and_ones);
                } else {
                    return $hundreds[$hundreds_digit];
                }
            }
        } elseif ($number < 1000000) {
            $thousands_digit = intval($number / 1000);
            $remainder = $number % 1000;
            if ($remainder > 0) {
                return $thousands[$thousands_digit] . ' ' . $this->numberToWord($remainder);
            } else {
                return $thousands[$thousands_digit];
            }
        }
    }

    function getInvoicers() {
        $dane = Nabywcy::all();
        return response()->json([
           'dane' => $dane
        ]);
    }

    function getInvoicersByNIP(Request $request) {
        $firmy = Nabywcy::where('NIP', $request->input('NIP'))->first();
        return response()->json([
            $firmy
        ]);
    }
    function getInvoicersById(Request $request) {
        $osoby = Nabywcy::where('id', $request->input('id'))->first();
        return response()->json([
            $osoby
        ]);
    }

    function saveInvoicer(Request $request) {
        $nazwa = $request->input('nazwa_firmy');
        $adres = $request->input('adres_firmy');
        $kodpoczt = $request->input('kod_pocztowy');
        $miasto = $request->input('miasto');
        $email = $request->input('email');
        $nip = $request->input('nip');
        $typ = $request->input('typ');
        if($typ == 'firma'){
            $check = Nabywcy::where('NIP', $nip)->first();
        } else {
            $check = Nabywcy::where('email', $email)->where('nazwa_firmy', $nazwa)->first();
        }
        if($check) {
            if($typ == 'firma'){
                return response()->json([
                    'error' => [
                        'message' => "Ta firma jest już zapisana."
                    ]
                ]);
            } else if($typ == 'osfiz'){
                return response()->json([
                    'error' => [
                        'message' => "Ta osoba jest już zapisana."
                    ]
                ]);
            }
        } else {
            $nabywca = new Nabywcy();
            $nabywca->nazwa_firmy = $nazwa;
            $nabywca->adres_firmy = $adres;
            $nabywca->kod_pocztowy = $kodpoczt;
            $nabywca->miasto = $miasto;
            $nabywca->nip = $nip;
            $nabywca->email = $email;
            $nabywca->typ = $typ;
            $nabywca->save();
            return response()->json([
                'message' => 'Zapisano!'
            ]);
        }
    }
}
