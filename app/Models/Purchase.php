<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    
    // Izinkan kolom ini diisi
    protected $guarded = ['id']; 
    
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