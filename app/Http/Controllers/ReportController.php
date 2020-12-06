<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;

class ReportController extends Controller
{
    private $selectedDate;

    public function index(Request $request)
    {
        $getDate = $this->selectedDate = $request->date;

        $filter = $request->filter;
        $filter = ($filter == '') ? 1 : $filter;
        $order = $this->_order($filter);
        $income = $this->_income($filter);
        $reports = $this->_reports($filter);
        $date = '';
        $dateRange = '';

        if ($filter == 7) {
            $date = Carbon::parse($this->selectedDate)->isoFormat('dddd, DD MMM YYYY');
        }
        else if ($filter == 8) {
            $splitDate = $this->_splitDate($this->selectedDate);

            $fromDate = Carbon::parse($splitDate[0])->isoFormat('dddd, DD MMM YYYY');
            $toDate = Carbon::parse($splitDate[1])->isoFormat('dddd, DD MMM YYYY');

            $dateRange = 'dari '. $fromDate .' sampai '. $toDate;
        }

        $filterTitle = [
            1 => 'Semua Data',
            'Hari Ini',
            'Kemarin',
            'Minggu Ini',
            'Bulan Ini',
            'Tahun Ini',
            $date,
            $dateRange
        ];

        return view('admin.report', compact('filter', 'filterTitle', 'getDate', 'income', 'order', 'reports'));
    }

    private function _order($filter = 1): Int
    {
        $order = 0;

        if ($filter == 1)
        {
            $order = Order::where('status', 3)->count();
        }
        else if ($filter == 2)
        {
            $order = Order::where(['status' => 3, 'created_at' => Carbon::today()])->count();
        }
        else if ($filter == 3)
        {
            $order = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::yesterday()->startOfDay()],
                ['created_at', '<=', Carbon::yesterday()->endOfDay()]
            ])->count();

        }
        else if ($filter == 4)
        {
            $order = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::now()->startOfWeek()],
                ['created_at', '<=', Carbon::now()->endOfWeek()]
            ])->count();
        }
        else if ($filter == 5)
        {
            $order = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::now()->startOfMonth()],
                ['created_at', '<=', Carbon::now()->endOfMonth()]
            ])->count();
        }
        else if ($filter == 6)
        {
            $order = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::now()->startOfYear()],
                ['created_at', '<=', Carbon::now()->endOfYear()]
            ])->count();
        }
        else if ($filter == 7)
        {
            $order = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::parse($this->selectedDate)->startOfDay()],
                ['created_at', '<=', Carbon::parse($this->selectedDate)->endOfDay()]
            ])->count();

        }
        else if ($filter == 8) {
            $date = $this->_splitDate($this->selectedDate);
            $fromDate = $date[0];
            $toDate = $date[1];

            $order = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::parse($fromDate)->startOfDay()],
                ['created_at', '<=', Carbon::parse($toDate)->endOfDay()]
            ])->count();
        }

        return $order;
    }

    private function _income($filter): Float
    {
        $income = 0.00;

        if ($filter == 1)
        {
            $income = Order::where('status', 3)->sum('total_price');
        }
        else if ($filter == 2)
        {
            $income = Order::where(['status' => 3, 'created_at' => Carbon::today()])->sum('total_price');
        }
        else if ($filter == 3)
        {
            $income = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::yesterday()->startOfDay()],
                ['created_at', '<=', Carbon::yesterday()->endOfDay()]
            ])->sum('total_price');
        }
        else if ($filter == 4)
        {
            $income = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::now()->startOfWeek()],
                ['created_at', '<=', Carbon::now()->endOfWeek()]
            ])->sum('total_price');
        }
        else if ($filter == 5)
        {
            $income = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::now()->startOfMonth()],
                ['created_at', '<=', Carbon::now()->endOfMonth()]
            ])->sum('total_price');
        }
        else if ($filter == 6)
        {
            $income = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::now()->startOfYear()],
                ['created_at', '<=', Carbon::now()->endOfYear()]
            ])->sum('total_price');
        }
        else if ($filter == 7)
        {
            $income = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::parse($this->selectedDate)->startOfDay()],
                ['created_at', '<=', Carbon::parse($this->selectedDate)->endOfDay()]
            ])->sum('total_price');
        }
        else if ($filter == 8) {
            $date = $this->_splitDate($this->selectedDate);
            $fromDate = $date[0];
            $toDate = $date[1];

            $income = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::parse($fromDate)->startOfDay()],
                ['created_at', '<=', Carbon::parse($toDate)->endOfDay()]
            ])->sum('total_price');
        }

        return $income;
    }

    private function _reports($filter): Object
    {
        $reports = new stdClass();

        if ($filter == 1)
        {
            $reports = Order::where('status', 3)->with('waiter')->paginate(10);
        }
        else if ($filter == 2)
        {
            $reports = Order::where(['status' => 3, 'created_at' => Carbon::today()])->with('waiter')->paginate(10);
        }
        else if ($filter == 3)
        {
            $reports = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::yesterday()->startOfDay()],
                ['created_at', '<=', Carbon::yesterday()->endOfDay()]
            ])->with('waiter')->paginate(10);
        }
        else if ($filter == 4)
        {
            $reports = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::now()->startOfWeek()],
                ['created_at', '<=', Carbon::now()->endOfWeek()]
            ])->with('waiter')->paginate(10);
        }
        else if ($filter == 5)
        {
            $reports = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::now()->startOfMonth()],
                ['created_at', '<=', Carbon::now()->endOfMonth()]
            ])->with('waiter')->paginate(10);
        }
        else if ($filter == 6)
        {
            $reports = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::now()->startOfYear()],
                ['created_at', '<=', Carbon::now()->endOfYear()]
            ])->with('waiter')->paginate(10);
        }
        else if ($filter == 7)
        {
            $reports = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::parse($this->selectedDate)->startOfDay()],
                ['created_at', '<=', Carbon::parse($this->selectedDate)->endOfDay()]
            ])->with('waiter')->paginate(10);
        }
        else if ($filter == 8) {
            $date = $this->_splitDate($this->selectedDate);
            $fromDate = $date[0];
            $toDate = $date[1];

            $reports = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::parse($fromDate)->startOfDay()],
                ['created_at', '<=', Carbon::parse($toDate)->endOfDay()]
            ])->with('waiter')->paginate(10);
        }

        return $reports;
    }

    public function _splitDate($date): Array
    {
        $splittedDate = explode('][', $date);

        $fromDate = str_replace('[', '', $splittedDate[0]);
        $toDate = str_replace(']', '', $splittedDate[1]);

        return [$fromDate, $toDate];
    }
}
