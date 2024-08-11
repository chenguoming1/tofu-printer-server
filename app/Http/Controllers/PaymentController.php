<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    function generateSignatureArray($secret, array $args) {   
        $hmacSource = [];        
        foreach ($args as $key => $val) {
          $hmacSource[$key] = "{$key}{$val}";
        }
        ksort($hmacSource);
        $sig            = implode("", array_values($hmacSource));
        $calculatedHmac = hash_hmac('sha256', $sig, $secret); 
      
        return $calculatedHmac;
    }


    function calcPrice($selectedOptions) {
        $hitPayPricing = config('hitpay.pricing');

        $jobType = $selectedOptions['job_type'];
        $noOfCopy = $selectedOptions["option_items"]['quantity'];
        $paperSize = $selectedOptions["option_items"]['paper_size'];
        $colorMode = $selectedOptions["option_items"]['color_mode'];
        $sides = $selectedOptions["option_items"]['sides'];

        $price = $hitPayPricing[$jobType][$paperSize][$colorMode][$sides] * $noOfCopy;

        return $price;
    }

    /**
     * Initialize payment process
     */
    public function store(Request $request)
    {

        $selectedOptions = $request->input('data');
    
        $amount = $this->calcPrice($selectedOptions);
        dd($amount);
        $jobRef = '1234567890';
        $location = '';
        $jobType = $selectedOptions['job_type'];
        $category = $selectedOptions['sub_category'];
        $qty = $selectedOptions["option_items"]['quantity'];
        $paymentMethod = 'paynow_online';
        $status = 'pending';

        $params = [
            "reference_number" => $jobRef,
            "amount" => $amount,
            "currency" => "sgd",
            "payment_methods" => ["paynow_online"],
            "generate_qr" => true,
            "expiry_date" => now('Asia/Singapore')->addMinutes(3)->toDateTimeString()
        ];

        $baseUrl = config('hitpay.api_base_url');
        $apiPath = '/v1/payment-requests';
        $hitPayApiKey = config('hitpay.api_key');

        $response = Http::withHeaders([
            'X-BUSINESS-API-KEY' => $hitPayApiKey,
            'X-Requested-With' => 'XMLHttpRequest'
        ])->asForm()->post($baseUrl . $apiPath, $params);

        $paymentInitResult = $response->json();

        $result = [
            'payment_ref' => $jobRef,
            'qr_code_data' => $paymentInitResult['qr_code_data']
        ];

        return response()->json($result);
    }
}
