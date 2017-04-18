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

Route::get('/', function () {

    //Put File
    //\Illuminate\Support\Facades\Storage::putFileAs('photo2', new \Illuminate\Http\File('/var/www/projects/php55/sites/s3presentation/files/Example.png'), 'Example.png');

    //Delete File
    //var_dump(\Illuminate\Support\Facades\Storage::delete('Example.png'));

    //Check exists
    //var_dump(\Illuminate\Support\Facades\Storage::exists('Example.png'));

    //Get Url
    //echo \Illuminate\Support\Facades\Storage::url('Example.png');

    echo PHP_EOL . 'ok';
});
