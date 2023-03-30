<?php
namespace Illuminate\View;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
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


// login route that goes to AuthController
Route::post('/login', [AuthController::class, 'login']);
// logout route that goes to AuthController and it is protected by sanctum
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
// signup route
Route::post('/signup', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    $user = User::create([
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);
    return $user->createToken($request->email)->plainTextToken;
});

// login and return token


Route::post('/sanctum/token', function (Request $request) {
    // $request->validate([
    //     'email' => 'required|email',
    //     'password' => 'required',
    //     'device_name' => 'required',
    // ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'error' => ['The provided credentials are incorrect.'],
        ]);
    }

     return $user->createToken($request->email)->plainTextToken;

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
