<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'EEMCI API is running.',
        'version' => '1.0.0',
    ]);
});
