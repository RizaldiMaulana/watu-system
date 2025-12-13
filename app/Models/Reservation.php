<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relationship to link pre-order transaction
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
