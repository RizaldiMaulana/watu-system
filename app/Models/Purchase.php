<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    
    // Izinkan kolom ini diisi
    protected $fillable = [
        'invoice_number',
        'supplier_id',
        'transaction_date',
        'total_amount',
        'paid_amount', // New
        'payment_method',
        'payment_term', // New
        'payment_status',
        'status', 
        'proof_file',
        'due_date',
        'due_date',
        'notes',
        'created_by',
        'signed_by',
        'signed_at',
        'delivery_proof', // Bukti Penerimaan (Image)
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function signer()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    public function payments()
    {
        return $this->hasMany(PurchasePayment::class);
    } 
    
    // Relasi ke item
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    protected $casts = [
        'transaction_date' => 'date',
        'due_date' => 'date',
    ];
}