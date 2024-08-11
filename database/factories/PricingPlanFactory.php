<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PricingPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'version' => '1',
            'job_type' => $this->faker->randomElement(['copy', 'scan', 'print']),
            'sub_category' => $this->faker->randomElement(['basic', 'id_card', 'passport', 'usb', 'email']),
            'variants' => [
                [
                    'a3' => [
                        'mono' => [
                            'single' => '0.10',
                            'double' => '0.20',
                        ],
                        'color' => [
                            'single' => '0.20',
                            'double' => '0.40',
                        ],
                    ],
                ],
                [
                    'a4' => [
                        'mono' => [
                            'single' => '0.10',
                            'double' => '0.20',
                        ],
                        'color' => [
                            'single' => '0.20',
                            'double' => '0.40',
                        ],
                    ],
                ],
                [
                    'a5' => [
                        'mono' => [
                            'single' => '0.10',
                            'double' => '0.20',
                        ],
                        'color' => [
                            'single' => '0.20',
                            'double' => '0.40',
                        ],
                    ],
                ],
            ],
            'is_in_use' => false,
        ];
    }
}
