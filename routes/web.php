<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('user', function() {
    return view('user');
});

Route::get('user-profile/{name}/{age}', function($name, $age) {
    return view('user',[
    'user_name' => $name,
    'age' => $age
    ]);
});