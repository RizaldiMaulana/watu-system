<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model {
    protected $guarded = ['id'];

    public function ingredient() {
        return $this->belongsTo(Ingredient::class);
    }
    
    public function product() {
        return $this->belongsTo(Product::class);
    }
}