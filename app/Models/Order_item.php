<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_item extends Model
{
    public $timestamps = false;
    public $fillable = ['food_id', 'item_qty', 'item_price'];
    
    public function orders()
    {
        return $this->belongsTo('App\Models\Order');
    }
}
