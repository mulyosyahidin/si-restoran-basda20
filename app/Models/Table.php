<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Table extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
}
