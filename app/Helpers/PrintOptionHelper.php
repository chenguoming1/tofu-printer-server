<?php
namespace App\Helpers;

use Illuminate\Contracts\Database\Eloquent\Builder;

class PrintOptionHelper
{
    public static function getRates($selectedColorOption, $selectedSideOption) {
        $printOptions = config('print_options');
        $colorRate = collect($printOptions['colors'])->firstWhere('name', $selectedColorOption)['rate'];
        $sideRate = collect($printOptions['sides'])->firstWhere('name', $selectedSideOption)['rate'];
        
        $rates = [
            'color' => $colorRate,
            'side' => $sideRate,
        ];

        return $rates;
    }

    public static function calcPrice($basePrice, $qty, $selectedColorOption, $selectedSideOption)
    {
        $rates = static::getRates($selectedColorOption, $selectedSideOption);

        $colorRate = $rates['color'];
        $sideRate = $rates['side'];

        $price = $basePrice * $colorRate  * $sideRate * $qty;

        return $price;
    }
}