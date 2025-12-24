<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FixedAsset extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'purchase_date',
        'cost',
        'salvage_value',
        'useful_life_years',
        'depreciation_accumulated',
        'book_value',
        'fixed_asset_account_id',
        'accumulated_depreciation_account_id',
        'depreciation_expense_account_id',
        'status',
    ];

    // Relationships to COA
    public function assetAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'fixed_asset_account_id');
    }

    public function accumulatedAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'accumulated_depreciation_account_id');
    }

    public function expenseAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'depreciation_expense_account_id');
    }

    // Calculation Helper
    public function calculateMonthlyDepreciation()
    {
        if ($this->useful_life_years <= 0) return 0;
        
        $depreciableAmount = $this->cost - $this->salvage_value;
        $months = $this->useful_life_years * 12;
        
        return $depreciableAmount / $months;
    }
}
