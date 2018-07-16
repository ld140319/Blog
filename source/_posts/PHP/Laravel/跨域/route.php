<?php
use Illuminate\Support\Facades\Route;

Route::get('/test', function (){
    return "hello, dish";
});

require "route_front.php";
require "route_backstage.php";