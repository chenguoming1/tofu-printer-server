<?php

namespace App\Http\Controllers;

use App\Http\Resources\PricingPlanResource;
use App\Models\PricingPlan;
use App\Models\Printer;
use Illuminate\Http\Request;
use App\Helpers\QueryHelper;
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
        $paperSizes = isset($variants['paper_sizes']) ? $variants['paper_sizes'] : [];
        if (!isset($paperSizes[$selectedOptionItems['paper_size']])) {
            return response()->json(['message' => 'Invalid paper size'], 400);
        }

        $colors = isset($paperSizes[$selectedOptionItems['paper_size']]['colors']) ? $paperSizes[$selectedOptionItems['paper_size']]['colors'] : [];
        if (!isset($colors[$selectedOptionItems['color']])) {
            return response()->json(['message' => 'Invalid color'], 400);
        }

        $sides = isset($colors[$selectedOptionItems['color']]['sides']) ? $colors[$selectedOptionItems['color']]['sides'] : [];
        if (!isset($sides[$selectedOptionItems['side']])) {
            return response()->json(['message' => 'Invalid side'], 400);
        }

        $price = $sides[$selectedOptionItems['side']];
        $totalPrice = $price * $quantity;

        return response()->json(['data' => ['price' => $totalPrice]]);
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

        $printerId = $request->header('Printer-Id');
        if (!$printerId) {
            return response()->json(['message' => 'Printer-Id is required'], 400);
        }

        $printer = Printer::findOrFail($printerId);
        $pricingPlanIds = $printer->pricing_plan_ids ?? [];
        $query = PricingPlan::query();
        $query = $query->whereIn('id', $pricingPlanIds);


        return new PricingPlanCollection($query->paginate($request->input('per_page', 20)));
    }
}