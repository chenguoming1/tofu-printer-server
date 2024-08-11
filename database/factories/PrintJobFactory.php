<?php

namespace Database\Factories;

use App\Models\PricingPlan;
use App\Models\Printer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PrintJobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pricingPlan = PricingPlan::factory()->create();
        $printer = Printer::factory()->create(['pricing_plan_ids' => [$pricingPlan->id]]);
        return [
            'printer_id' => $printer->id,
            'pricing_plan_id' => $pricingPlan->id,
            'job_type' => $this->faker->randomElement(['copy', 'scan', 'print']),
            'sub_category' => $this->faker->randomElement(['basic', 'id_card', 'passport', 'usb', 'email']),
            'status' => $this->faker->randomElement(['in_progress', 'done']),
            'quantity' => $this->faker->randomDigitNotNull,
            'amount' => $this->faker->randomFloat(2, 0, 100),
            'payment_type' => $this->faker->randomElement(['cash', 'card']),
            'payment_status' => $this->faker->randomElement(['pending', 'done']),
            'currency_code' => $this->faker->randomElement(['SGD']),
            'selected_option_items' => [
                'color' => $this->faker->randomElement(['mono', 'color']),
                'paper_size' => $this->faker->randomElement(['a3', 'a4', 'a5']),
                'orientation' => $this->faker->randomElement(['portrait', 'landscape']),
                'layout' => $this->faker->randomElement(['1up', '2up']),
                'sides' => $this->faker->randomElement(['single', 'double']),
            ],
        ];
    }
}
