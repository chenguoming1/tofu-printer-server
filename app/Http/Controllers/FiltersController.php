<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PricingVariantTemplatesController extends Controller
{
    function index(Request $request)
    {
        $filters = config('filters');

        return response()->json($filters);
    }
}