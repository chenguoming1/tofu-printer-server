<?php

namespace App\Http\Controllers;

use App\Http\Resources\PricingPlanResource;
use App\Models\PricingPlan;
use App\Models\Printer;
use Illuminate\Http\Request;
use App\Helpers\QueryHelper;
use App\Helpers\PrintOptionHelper;
use App\Http\Resources\PricingPlanCollection;

class PrinterPricingPlanController extends Controller
{

    function calcPrice(Request $request, PricingPlan $pricingPlan)
    {
        $validated = $request->validate([
            'data.selected_option_items.color' => 'required|in:color,mono',
            'data.selected_option_items.paper_size' => 'required|in:a3,a4,a5',
            'data.selected_option_items.side' => 'required|in:single,double',
            'data.quantity' => 'required|int|min:1|max:1000',
        ]);
        
        $quantity = $validated['data']['quantity'];
        $selectedOptionItems = $validated['data']['selected_option_items'];
        $variants = $pricingPlan->variants;
        $basePrices = $variants['base_prices'];

        $basePrice = $basePrices[$selectedOptionItems['paper_size']] ?? 0.0;
        $selectedColorOption = $selectedOptionItems['color'];
        $selectedSideOption = $selectedOptionItems['side'];
        
        $amount = PrintOptionHelper::calcPrice($basePrice, $quantity, $selectedColorOption, $selectedSideOption);

        return response()->json(['data' => ['price' => $amount, 'amount' => $amount]]);
    }

    function getPrinterPricingPlan(Request $request)
    {
        $filters = $request->input('filters', []);
        $jobType = collect($filters)->first(function ($filter) {
            $filter = json_decode($filter, true);
            if ($filter['key'] == 'job_type') {
                return $filter['value'];
            }
        });
        if (!$jobType) {
            return response()->json(['message' => 'Job type is required'], 400);
        }

        $printerName = $request->header('Printer-Name');
        if (!$printerName) {
            return response()->json(['message' => 'Printer-Name is required'], 400);
        }

        $printer = Printer::whereName($printerName)->first();
        $pricingPlanIds = $printer->pricing_plan_ids ?? [];
        $query = PricingPlan::query();
        $query = $query->whereIn('id', $pricingPlanIds);

        $query = QueryHelper::getQuery($request, $query, $filters);
        return new PricingPlanCollection($query->paginate($request->input('per_page', 20)));
    }
}