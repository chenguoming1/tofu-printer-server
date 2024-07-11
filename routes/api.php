<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/options', function () {
        $response = [
            'message' => 'retrieved data successfully',
            'data' => config('printoptions')
        ];
        return $response;    
    });
});
