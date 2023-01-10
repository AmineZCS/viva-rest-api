<?php
namespace Illuminate\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Dompdf\Dompdf;
use App\Models\Viva;
use App\Http\Controllers\PDFController;

use Illuminate\Support\Facades\Mail;
use App\Mail\VivaAdded;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// get user info
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




// login and return token

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

     return $user->createToken($request->device_name)->plainTextToken;

});



// logout and delete token
Route::middleware('auth:sanctum')->get('/user/revoke', function (Request $request) {
    $user = $request->user();
    return $user->tokens()->delete();
});

// , JSON_FORCE_OBJECT);
// testing how to generate secret key
Route::get('/code', function (Request $request) {
    return substr(str_shuffle(base64_encode("amine ,benamrouche")), 0, 7);
});

// Create New Viva
Route::middleware('auth:sanctum')->post('/viva/create', function (Request $request) {
    $project_name = $request->project_name;
    $year = $request->year;
    $sup_mark = $request->sup_mark;
    $pre_mark = $request->pre_mark;
    $exa_mark = $request->exa_mark;
    $sup_name = $request->sup_name;
    $pre_name = $request->pre_name;
    $exa_name = $request->exa_name;
    $final_mark = $request->final_mark;
    $students = json_encode($request->students);
    $code =  substr(str_shuffle(base64_encode($request->students[0])), 0, 7);
    $viva = Viva::create([
        'project_name' => $project_name ,
        'year' => $year ,
        'sup_mark' => $sup_mark ,
        'pre_mark' => $pre_mark ,
        'exa_mark' => $exa_mark ,
        'sup_name' => $sup_name ,
        'pre_name' => $pre_name ,
        'exa_name' => $exa_name ,
        'final_mark' => $final_mark ,
        'students' => $students ,
        'code' => $code
    ]);


    return $viva  ;
});


Route::middleware('auth:sanctum')->post('/viva', function (Request $request) {

    $viva = Viva::where('code', $request->code)
        ->get();
    return $viva;
});





// generate pdf and download it
Route::middleware('auth:sanctum')->get('/pdf', function (Request $request) {
    $dompdf = new Dompdf();
$dompdf->loadHtml('hello world');

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
return $dompdf->stream();
});

Route::middleware('auth:sanctum')->get('/pdff', function (Request $request) {

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
       'students' => $viva[0]['students'],
       'code' => $code,
       'full_name' => $full_Name
];
    return view('pdf', $data_array);
});
Route::middleware('auth:sanctum')->post('/sendPDF', [PDFController::class, 'sendPDF']);

Route::get('/htmltopdf', [PDFController::class, 'generatePDF']);
Route::middleware('auth:sanctum')->post('/htmltopdff',function (Request $request) {

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
});
Route::middleware('auth:sanctum')->post('/pp', function (Request $request) {
    $code = $request->code;
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
       'students' => (json_decode($viva[0]['students'],true))];
    return view('welcome',$data_array);
});

// send email
Route::middleware('auth:sanctum')->post('/sendEmail', function (Request $request) {


        /**
         * Store a receiver email address to a variable.
         */
        // $request->user()->email;
        $reveiverEmailAddress = $request->user()->email;
        return Mail::to($reveiverEmailAddress)->send(new VivaAdded());

        /**
         * Check if the email has been sent successfully, or not.
         * Return the appropriate message.
         */

});

