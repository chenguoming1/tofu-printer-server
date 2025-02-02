<?php

namespace App\Http\Controllers;

use App\Http\Resources\PricingPlanResource;
use App\Models\PricingPlan;
use App\Models\Printer;
use Illuminate\Http\Request;
use App\Helpers\QueryHelper;
use App\Helpers\PrintOptionHelper;
use App\Http\Resources\PricingPlanCollection;
use App\Models\PrintJob;

class PrintJobController extends Controller
{
    /**
     * Initialize payment process
     */
    public function store(Request $request)
    {
        $printerName = $request->header('Printer-Name');
        if (!$printerName) {
            return response()->json(['message' => 'Printer-Name is required'], 400);
        }

        $printer = Printer::whereName($printerName)->first();
        if (!$printer) {
            return response()->json(['message' => 'Printer not found'], 404);
        }

        $pricingPlanIds = $printer->pricing_plan_ids ?? [];
        if (empty($pricingPlanIds)) {
            return response()->json(['message' => 'Printer has no pricing plans'], 404);
        }
    
        // $pricingPlan = PricingPlan::find($request->input('pricing_plan_id'));
        $data = $request->input('data');
        $pricePlanId = $data['pricing_plan_id'];

        if (!in_array($pricePlanId, $pricingPlanIds)) {
            return response()->json(['message' => 'Printer does not have this pricing plan'], 400);
        }

        $pricingPlan = PricingPlan::find($pricePlanId);

        $validated = $request->validate([
            'data.selected_option_items.color' => 'required|in:color,mono',
            'data.selected_option_items.paper_size' => 'required|in:a3,a4,a5',
            'data.selected_option_items.side' => 'required|in:single,double',
            'data.selected_option_items.darkness' => 'int|min:1|max:1000',
            'data.quantity' => 'required|int|min:1|max:1000',
        ]);

        $qty = $validated['data']['quantity'];
        $selectedOptions = $validated['data']['selected_option_items'];
        $selectedColorOption = $selectedOptions['color'];
        $selectedSideOption = $selectedOptions['side'];
        $basePrice = $pricingPlan->base_price;

        $amount = PrintOptionHelper::calcPrice($basePrice, $qty, $selectedColorOption, $selectedSideOption);

        $printerJob = new PrintJob();
        $printerJob->job_no = $printer->id . str_replace('.', '', microtime(true));
        $printerJob->printer_id = $printer->id;
        $printerJob->pricing_plan_id = $pricingPlan->id;
        $printerJob->job_type = $pricingPlan->job_type;
        $printerJob->sub_category = $pricingPlan->sub_category;
        $printerJob->status = PrintJob::JOB_STATUS_IN_PROGRESS;
        $printerJob->quantity = $qty;
        $printerJob->amount = $amount;
        $printerJob->currency_code = 'SGD';
        $printerJob->payment_type = PrintJob::PAYMENT_ENETS;
        $printerJob->payment_status = PrintJob::PAYMENT_STATUS_PENDING;
        $printerJob->selected_option_items = json_encode($selectedOptions);
        $printerJob->save();

        return response()->json(['message' => 'Job created successfully', 'data' => ['id' => $printerJob->id, 'job_no' => $printerJob->job_no]]);
    }


    /**
     * Initialize payment process
     */
    public function update(Request $request, PrintJob $printJob)
    {
        $validated = $request->validate([
            'data.status' => 'required|in:done,cancelled',
            'data.payment_status' => 'required|in:success,failed',
        ]);

        $data = $validated['data'];

        $printJob->status = $data['status'];
        $printJob->payment_status = $data['payment_status'];
        $printJob->save();

        return response()->json(['message' => 'Job updated successfully']);
    }
}