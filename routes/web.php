<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    return view('welcome');
});
    Route::post('/pdff', function (Request $request) {
        $project_name = $request->project_name;
        $year = $request->year;
        $sup_mark = $request->sup_mark;
        $pre_mark = $request->pre_mark;
        $exa_mark = $request->exa_mark;
        $sup_name = $request->sup_name;
        $pre_name = $request->pre_name;
        $exa_name = $request->exa_name;
        $final_mark = $request->final_mark;
        return view('pdf', ['name' => $project_name ,
         'year' => $year ,
         'sup_mark' => $sup_mark ,
          'pre_mark'=> $pre_mark ,
          'exa_mark' => $exa_mark,
            'sup_name' => $sup_name,
            'pre_name' => $pre_name,
            'exa_name' => $exa_name,
            'final_mark' => $final_mark
    ]);
    });
