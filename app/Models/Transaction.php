<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
    
    public function payments()
    {
        return $this->hasMany(TransactionPayment::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    // Use 'uuid' for route model binding if we want to use it implicitly
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array
     */
    public function uniqueIds()
    {
        return ['uuid'];
    }

    protected $casts = [
        'is_complimentary' => 'boolean',
        'voided_at' => 'datetime',
        'service_charge_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'subtotal_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function getIsVoidAttribute()
    {
        return !is_null($this->voided_at);
    }
}