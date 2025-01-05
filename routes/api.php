<?php

use App\Http\Controllers\FiltersController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\PrinterResource;
use App\Http\Resources\PrintJobResource;
use App\Http\Resources\PricingPlanResource;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PricingVariantTemplatesController;
use App\Http\Resources\PricingPlanCollection;
use App\Models\PricingPlan;
use App\Models\Printer;
use App\Models\PrintJob;
use App\Helpers\QueryHelper;
use App\Http\Controllers\PricingPlanController;
use App\Http\Controllers\PrintJobController;
use App\Http\Controllers\PrinterPricingPlanController;
use App\Http\Resources\PrinterCollection;
use App\Http\Resources\PrintJobCollection;

Route::prefix('v1')->group(function () {

    ########################## Auth ##########################
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
    Route::delete('/logout', [LoginController::class, 'logout'])->middleware('auth:api');


    Route::get('/options', function () {
        $subCategories = config('filters.sub_categories');
        $printOptions = config('mobile_print_options');

        $response = [
            'message' => 'retrieved data successfully',
            'data' => []
        ];

        collect($subCategories)->map(function ($subCategory) use (&$response, $printOptions) {
            $response['data'][$subCategory['name']] = [
                'name' => $subCategory['name'],
                'display_name' => $subCategory['display_name'],
                'option_items' => $printOptions
            ];
        });

        return $response;
    });

    ########################## Printers ##########################
    Route::get('/printers', function (Request $request) {
        $query = QueryHelper::getQuery($request, Printer::query(), []);
        return new PrinterCollection($query->paginate($request->input('per_page', 20)));
    });
    Route::get('/printers/{id}', function (Request $request, string $id) {
        return new PrinterResource(Printer::findOrFail($id));
    });
    Route::delete('/printers/{id}', function (string $id) {
        Printer::findOrFail($id)->delete();
        return response()->json(['message' => 'deleted successfully']);
    });
    Route::post('/printers', function (Request $request) {
        $printer = Printer::create($request->all());
        return new PrinterResource($printer);
    });
    Route::put('/printers/{id}', function (Request $request, string $id) {
        $printer = Printer::findOrFail($id);
        $printer->update($request->all());
        return new PrinterResource($printer);
    });

    ########################## Pricing Plans ##########################
    Route::get('/pricing_plans', function (Request $request) {
        $query = QueryHelper::getQuery($request, PricingPlan::query(), []);
        return new PricingPlanCollection($query->paginate($request->input('per_page', 20)));
    });
    Route::get('/pricing_plans/{id}', function (Request $request, string $id) {
        return new PricingPlanResource(PricingPlan::findOrFail($id));
    });
    Route::delete('/pricing_plans/{id}', function (string $id) {
        PricingPlan::findOrFail($id)->delete();
        return response()->json(['message' => 'deleted successfully']);
    });
    Route::post('/pricing_plans', [PricingPlanController::class, 'store']);
    Route::put('/pricing_plans/{pricingPlan}', [PricingPlanController::class, 'update']);

    ########################## Print Job ##########################
    Route::get('/print_jobs', function (Request $request) {
        $query = QueryHelper::getQuery($request, PrintJob::query(), []);
        return new PrintJobCollection($query->paginate($request->input('per_page', 20)));
    });

    Route::get('/filters', [FiltersController::class, 'index'])->name('filters_and_templates');
    Route::get('/pricing_variant_templates', [PricingVariantTemplatesController::class, 'index'])->name('pricing_variant_templates');
    ########################## mobile apis ##########################
    Route::get('/printer_pricing_plans', [PrinterPricingPlanController::class, 'getPrinterPricingPlan'])->name('getPrinterPricingPlan');

    Route::post('/print_jobs', [PrintJobController::class, 'store'])->name('store_printer_job');
    Route::put('/print_jobs/{printJob}', [PrintJobController::class, 'update'])->name('update_printer_job');

    Route::post('/calc_price/{pricingPlan}', [PrinterPricingPlanController::class, 'calcPrice'])->name('calc_price');
});
