<?php

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

Route::get('/', function() {
    return view('index');
});

Route::get('/map', function() {
    return view('map');
});

Route::get('/myaccount', function() {
    return view('myaccount');
});

Route::get('/app', function() {
    return view('app');
})->where('any', '.*');


// データ受け渡しを行う際に使用
// Route::get('/tests/{post}', 'TestController@show');
Auth::routes();

// Route::get('/myaccount', 'HomeController@index')->name('myaccount');
