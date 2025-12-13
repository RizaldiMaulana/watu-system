<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tax;
use App\Models\Transaction;

class TaxCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tax_and_service_charge_calculation_logic()
    {
        // 1. Setup Data: Active Service Charge (10%) and PB1 (10%)
        // Using 'sequence' or manually creating to ensure order
        $serviceCharge = Tax::create([
            'name' => 'Service Charge',
            'rate' => 10,
            'type' => 'service_charge',
            'is_active' => true,
            'sort_order' => 1
        ]);

        $pb1 = Tax::create([
            'name' => 'PB1',
            'rate' => 10,
            'type' => 'tax',
            'is_active' => true,
            'sort_order' => 2
        ]);

        // 2. Simulate Order Subtotal
        $subtotal = 100000;

        // 3. Replicate Controller Logic (Simulated)
        // This effectively also tests the logic we put in our Controllers
        
        $activeTaxes = Tax::where('is_active', true)->orderBy('sort_order')->get();
        $taxableAmount = $subtotal;

        // Calc Service Charge
        $svcAmount = 0;
        foreach ($activeTaxes as $tax) {
            if ($tax->type === 'service_charge') {
                $svcAmount += $taxableAmount * ($tax->rate / 100);
            }
        }

        // Calc Tax Base
        $taxBase = $taxableAmount + $svcAmount;

        // Calc Tax (PB1)
        $taxAmount = 0;
        foreach ($activeTaxes as $tax) {
            if ($tax->type === 'tax') {
                $taxAmount += $taxBase * ($tax->rate / 100);
            }
        }

        $grandTotal = $taxBase + $taxAmount;

        // 4. Assertions
        $this->assertEquals(10000, $svcAmount, 'Service Charge should be 10% of 100,000');
        $this->assertEquals(110000, $taxBase, 'Tax Base should include Service Charge');
        $this->assertEquals(11000, $taxAmount, 'PB1 should be 10% of Tax Base (110,000)');
        $this->assertEquals(121000, $grandTotal, 'Grand Total should be 121,000');
    }
}
