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

/*
$this->get('/', function () {
    return File::get(public_path() . '/js/index.html');
});
*/


$this->get('/', function () {
    return view('abc');
});

