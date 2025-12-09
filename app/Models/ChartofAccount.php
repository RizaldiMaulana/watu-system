<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function journalDetails()
    {
        return $this->hasMany(JournalDetail::class, 'account_id');
    }
}