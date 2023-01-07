<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;

use Dompdf\Dompdf;
use App\Models\User;
use App\Models\Viva;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generatePDF(Request $request){
        $code = $request->code;
        // $full_Name = $request->user()->name;
        $viva = Viva::where('code', $code)->get();
        $data_array = ['name' => $viva[0]['project_name'] ,
        'year' => $viva[0]['year'] ,
        'sup_mark' => $viva[0]['sup_mark'] ,
         'pre_mark'=> $viva[0]['pre_mark'] ,
         'exa_mark' => $viva[0]['exa_mark'],
           'sup_name' => $viva[0]['sup_name'],
           'pre_name' => $viva[0]['pre_name'],
           'exa_name' => $viva[0]['exa_name'],
           'final_mark' => $viva[0]['final_mark'],
           'students' => $viva[0]['students'],
           'code' => $code,
           'full_name' => $full_Name
        //    ,
        //    'full_name' => $full_Name
    ];
    $pdf = PDF::loadView('pdf', $data_array);

    return $pdf->stream();
    }


 public function sendPDF(Request $request){

$code = $request->code;
$full_Name = $request->user()->name;

$viva = Viva::where('code', $code)->get();
$data_array = ['name' => $viva[0]['project_name'] ,
'year' => $viva[0]['year'] ,
'sup_mark' => $viva[0]['sup_mark'] ,
 'pre_mark'=> $viva[0]['pre_mark'] ,
 'exa_mark' => $viva[0]['exa_mark'],
   'sup_name' => $viva[0]['sup_name'],
   'pre_name' => $viva[0]['pre_name'],
   'exa_name' => $viva[0]['exa_name'],
   'final_mark' => $viva[0]['final_mark'],
   'students' => (json_decode($viva[0]['students'],true)),
   'code' => $code,
   'full_name' => $full_Name
];
$pdf = new Dompdf();
$pdf->load_html(view('pdf', $data_array));
// $pdf->setPaper('A4', 'horizontal');
$pdf->render();
return $pdf->stream();
    }


}

