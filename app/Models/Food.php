<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Food extends Model implements HasMedia
{
    use InteractsWithMedia;
    public $table = 'foods';

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category');
    }

    public function order_item()
    {
        return $this->belongsTo('App\Models\Order_item');
    }
}
