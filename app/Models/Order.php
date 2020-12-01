<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use DatePeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function waiter()
    {
        return $this->hasOne('App\User', 'id', 'waiter_id');
    }

    public static function income()
    {
        return self::where('status', 3)->sum('total_price');
    }

    public static function weekStats()
    {
        $results = self::whereBetween('created_at', [Carbon::now()->subDays(6)->format('Y-m-d') .' 00:00:00', Carbon::now()->format('Y-m-d') .' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date')
            ->get([
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date'),
                DB::raw('count(*) as total')
            ])
            ->keyBy('date')
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date);
                return $item;
            });

        $period = new DatePeriod(Carbon::now()->subDays(6), CarbonInterval::day(), Carbon::now()->addDay());

        $graph = array_map(function ($datePeriod) use ($results) {
            $date = $datePeriod->format('Y-m-d');
            return $results->has($date) ? $results->get($date)->total : 0;
        }, iterator_to_array($period));

        return $graph;
    }

    public static function mostOrders()
    {
        
    }

    public static function todayIncome() 
    {
        return self::where(['status' => 3, 'created_at' => Carbon::today()])->sum('total_price');
    }

}
