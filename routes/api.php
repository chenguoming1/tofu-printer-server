<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\PrinterCollection;
use App\Http\Resources\PrinterResource;
use App\Models\Printer;

Route::prefix('v1')->group(function () {
    Route::get('/options', function () {
        $response = [
            'message' => 'retrieved data successfully',
            'data' => config('printoptions')
        ];
        return $response;    
    });

    Route::post('/payment/init', [PaymentController::class, 'store']);
    Route::get('/payment/status', [PaymentController::class, 'get']);

    ########################## Printers ##########################
    Route::get('/printers', function (Request $request) {
        return new PrinterCollection(Printer::paginate($request->input('per_page', 20)));
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
});
