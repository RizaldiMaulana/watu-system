<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'options' => 'array',
    ];
    
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}