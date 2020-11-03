<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function items()
    {
        return $this->hasMany('App\Models\Order_item');
    }

    public function table()
    {
        return $this->hasOne('App\Models\Table', 'id', 'table_id');
    }
}
