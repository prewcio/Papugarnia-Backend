<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TicketPrices;
use App\Models\Voucher;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class InfoControl extends Controller
{
    public function getPrices(){
        $ticketPrices = TicketPrices::all();
        return response()->json($ticketPrices);
    }
    public function setPrices(Request $request){
        $bnPrice = $request->input('biletNormalny');
        $buPrice = $request->input('biletUlgowy');
        $br1Price = $request->input('biletRodzinny1');
        $br2Price = $request->input('biletRodzinny2');
        $br1dPrice = $request->input('biletRodzinny1dd');
        $br2dPrice = $request->input('biletRodzinny2dd');
        $bgnPrice = $request->input('biletGrupowyN');
        $bguPrice = $request->input('biletGrupowyU');
        $fPrice = $request->input('karma');
        $tPrice = $request->input('przysmak');

        $bn = TicketPrices::where('ticketName', 'biletnormalny')->first();
        if($bn){
            $bn->ticketPrice = $bnPrice;
            $bn->save();
        }

        $bu = TicketPrices::where('ticketName', 'biletulgowy')->first();
        if($bu){
            $bu->ticketPrice = $buPrice;
            $bu->save();
        }

        $br1 = TicketPrices::where('ticketName', 'biletrodzinny1')->first();
        if($br1){
            $br1->ticketPrice = $br1Price;
            $br1->save();
        }

        $br2 = TicketPrices::where('ticketName', 'biletrodzinny2')->first();
        if($br2){
            $br2->ticketPrice = $br2Price;
            $br2->save();
        }

        $br1d = TicketPrices::where('ticketName', 'biletrodzinny1dd')->first();
        if($br1d){
            $br1d->ticketPrice = $br1dPrice;
            $br1d->save();
        }

        $br2d = TicketPrices::where('ticketName', 'biletrodzinny2dd')->first();
        if($br2d){
            $br2d->ticketPrice = $br2dPrice;
            $br2d->save();
        }
        $bgn= TicketPrices::where('ticketName', 'biletgrupowy')->first();
        if($bgn){
            $bgn->ticketPrice = $bgnPrice;
            $bgn->save();
        }
        $bgu = TicketPrices::where('ticketName', 'biletgrupowyulg')->first();
        if($bgu){
            $bgu->ticketPrice = $bguPrice;
            $bgu->save();
        }
        $karma = TicketPrices::where('ticketName', 'karma')->first();
        if($karma){
            $karma->ticketPrice = $fPrice;
            $karma->save();
        }
        $przysmak = TicketPrices::where('ticketName', 'przysmak')->first();
        if($przysmak){
            $przysmak->ticketPrice = $tPrice;
            $przysmak->save();
        }
        return response()->json([
            'status'=>'Prices Changed.'
        ]);
    }

    public function checkVoucher(Request $request){
        $code = $request->input('voucherCode');
        $codeC = Voucher::where('kod', $code)->first();
        if(!$codeC){
            return response()->json([
                'type' => 'error',
                'state' => "Voucher nie istnieje."
            ]);
        }
        else{
            $used = $codeC->used;
            if($used){
                return response()->json([
                    'type' => 'error',
                    'state' => "Voucher został już wykorzystany - (".$codeC->ilosc_osob." os. - ".$codeC->type.")."
                ]);
            } else {
                $today = date("Y-m-d");
                if(strtotime($codeC->expire_date) < strtotime($today)){
                    return response()->json([
                        'type' => 'error',
                        'state' => "Voucher jest przeterminowany. Jego ważność wygasła: ".$codeC->expire_date
                    ]);
                } else {
                    return response()->json([
                        'type' => 'success',
                        'state' => $codeC->ilosc_osob. ' os.',
                        'expire' => $codeC->expire_date,
                        'voucherType' => $codeC->type
                    ]);
                }
            }
        }
    }

    public function useVoucher(Request $request){
        $code = $request->input('voucherCode');
        $codeC = Voucher::where('kod', $code)->first();
        if(!$codeC){
            return response()->json([
                'type' => 'error',
                'state' => "Voucher nie istnieje."
            ]);
        }
        else{
            $used = $codeC->used;
            if($used=='1'){
                return response()->json([
                    'type' => 'error',
                    'state' => "Voucher został już wykorzystany."
                ]);
            } else {
                $today = date("Y-m-d");
                if(strtotime($codeC->expire_date) < strtotime($today)){
                    return response()->json([
                        'type' => 'error',
                        'state' => "Voucher jest przeterminowany. Jego ważność wygasła: ".$codeC->expire_date
                    ]);
                } else {
                    $codeC->used = 1;
                    $codeC->save();
                    return response()->json([
                        'type' => 'success',
                        'state' => $codeC->ilosc_osob. ' os.',
                        'voucherType' => $codeC->type
                    ]);
                }
            }
        }
    }

    public function generateVoucher(Request $request){
        $ilosc = $request->input('ilosc');
        $characters = '0123456789ABCDEFGHIJKLMNOPRSTUVWXYZ';
        $type = $request->input('type');
        $charactersLength = strlen($characters);
        $code = '';
        for ($i =0; $i<16;$i++){
            $code .= $characters[rand(0, $charactersLength-1)];
        }
        $codeC = Voucher::where('kod', $code)->first();
        while($codeC){
            $code = '';
            for ($i =0; $i<16;$i++){
                $code .= $characters[rand(0, $charactersLength-1)];
            }
            $codeC = Voucher::where('kod', $code)->first();
        }

        $new = Carbon::now()->addMonths(6)->endOfDay();
        $voucher = new Voucher();
        $voucher->ilosc_osob = $ilosc;
        $voucher->kod = $code;
        $voucher->type = $type;
        $voucher->used = false;
        $voucher->expire_date = $new;
        $voucher->save();
        return response()->json([
            'count' => $ilosc,
            'invite_code' => $code,
            'type' => $type,
            'when_expires' => $new
        ]);
    }

    public function generateVoucherIMG(Request $request){
        $count = $request->input('count');
        $invite_code = $request->input('invite_code');
        $type = $request->input('type');
        if($type=="Normalny") {
            $type = "normalne";
        } else if($type=="Ulgowy") {
            $type = "ulgowe";
        }
        $headers = [
            'Content-Type' => 'application/pdf',
        ];
        $expire_date = Carbon::now()->addMonths(6)->endOfDay()->format('d.m.Y');
        $months = array(
            'stycznia', 'lutego', 'marca', 'kwietnia',
            'maja', 'czerwca', 'lipca', 'sierpnia',
            'września', 'października', 'listopada', 'grudnia'
          );
          
          $month_number = date('n', strtotime($expire_date));
          $month_name = $months[$month_number - 1];
          
          $formatted = date('j', strtotime($expire_date)) . ' ' . $month_name . ' ' . date('Y', strtotime($expire_date));
        $pdf = Pdf::loadView('zaproszenie', [
            'count' => $count,
            'invite_code' => $invite_code,
            'expire_date' => $expire_date,
            'type' => $type,
            'expire_text' => $formatted
        ]);
        $pdf->set_base_path(url('/style/Invite.css'));

        $pdf->save(public_path('Zaproszenie.pdf'));
        $file = public_path('Zaproszenie.pdf');
        return response()->download($file, 'Zaproszenie.pdf', $headers)->deleteFileAfterSend(true);

    }
}
