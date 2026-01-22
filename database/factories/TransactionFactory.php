<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_number' => 'INV-TEST-' . $this->faker->uuid,
            'customer_id' => \App\Models\Customer::factory(),
            'total_amount' => $this->faker->numberBetween(50000, 500000),
            'payment_status' => 'Paid',
            'payment_method' => 'Cash',
            'subtotal_amount' => 50000,
            'tax_amount' => 0,
            'tax_rate' => 0,
            'type' => 'Dine-in' // Default type
            // 'grand_total' removed
        ];
    }
}
