<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/hello', function (){
    print "Hello";
});
Route::get('/my-name', function (){
    print 'My name is Rustam';
});
