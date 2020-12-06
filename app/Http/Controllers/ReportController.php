<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
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

    public function export(Request $request, $filter, $date = NULL)
    {
        if ($filter == 8) {
            $splitDate = $this->_splitDate($date);

            $fromDate = Carbon::parse($splitDate[0])->isoFormat('dddd, DD MMM YYYY');
            $toDate = Carbon::parse($splitDate[1])->isoFormat('dddd, DD MMM YYYY');
        }

        $titles = [
            '1' => 'Semua Data Laporan',
            'Laporan '. Carbon::now()->isoFormat('dddd, DD MMM YYYY'),
            'Laporan '. Carbon::yesterday()->isoFormat('dddd, DD MMM YYYY'),
            'Laporan Minggu Ini',
            'Laporan Bulan '. date('F'),
            'Laporan Tahun '. date('Y'),
            'Laporan '. Carbon::parse($this->selectedDate)->isoFormat('dddd, DD MMM YYYY'),
            ($filter == 8) ? 'Laporan '. $fromDate .' sampai '. $toDate : ''
        ];

        $title = $titles[$filter];

        $this->selectedDate = $date;
        $reports = $this->_reports($filter, true);

        return Excel::download(new ReportExport($reports), $title .'.xlsx');
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
            $order = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::today()->startOfDay()],
                ['created_at', '<=', Carbon::today()->endOfDay()]
                ])->count();
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
            $income = Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::today()->startOfDay()],
                ['created_at', '<=', Carbon::today()->endOfDay()]
            ])->sum('total_price');
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

    private function _reports($filter, $export = false): Object
    {
        $reports = new stdClass();

        if ($filter == 1)
        {
            $reports = ($export == false) ? Order::where('status', 3)->with('waiter')->paginate(10) : Order::where('status', 3)->with('waiter')->get();
        }
        else if ($filter == 2)
        {
            $reports = ($export == false) ? Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::today()->startOfDay()],
                ['created_at', '<=', Carbon::today()->endOfDay()]
            ])->with('waiter')->paginate(10) : Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::today()->startOfDay()],
                ['created_at', '<=', Carbon::today()->endOfDay()]
            ])->with('waiter')->get();
        }
        else if ($filter == 3)
        {
            $reports = ($export == false) ? Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::yesterday()->startOfDay()],
                ['created_at', '<=', Carbon::yesterday()->endOfDay()]
            ])->with('waiter')->paginate(10) :
            Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::yesterday()->startOfDay()],
                ['created_at', '<=', Carbon::yesterday()->endOfDay()]
            ])->with('waiter')->get();
        }
        else if ($filter == 4)
        {
            $reports = ($export == false) ? Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::now()->startOfWeek()],
                ['created_at', '<=', Carbon::now()->endOfWeek()]
            ])->with('waiter')->paginate(10) :
            Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::now()->startOfWeek()],
                ['created_at', '<=', Carbon::now()->endOfWeek()]
            ])->with('waiter')->get();
        }
        else if ($filter == 5)
        {
            $reports = ($export == false) ? Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::now()->startOfMonth()],
                ['created_at', '<=', Carbon::now()->endOfMonth()]
            ])->with('waiter')->paginate(10) :
            Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::now()->startOfMonth()],
                ['created_at', '<=', Carbon::now()->endOfMonth()]
            ])->with('waiter')->get();
        }
        else if ($filter == 6)
        {
            $reports = ($export == false) ? Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::now()->startOfYear()],
                ['created_at', '<=', Carbon::now()->endOfYear()]
            ])->with('waiter')->paginate(10) :
            Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::now()->startOfYear()],
                ['created_at', '<=', Carbon::now()->endOfYear()]
            ])->with('waiter')->get();
        }
        else if ($filter == 7)
        {
            $reports = ($export == false) ? Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::parse($this->selectedDate)->startOfDay()],
                ['created_at', '<=', Carbon::parse($this->selectedDate)->endOfDay()]
            ])->with('waiter')->paginate(10) :
            Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::parse($this->selectedDate)->startOfDay()],
                ['created_at', '<=', Carbon::parse($this->selectedDate)->endOfDay()]
            ])->with('waiter')->get();
        }
        else if ($filter == 8) {
            $date = $this->_splitDate($this->selectedDate);
            $fromDate = $date[0];
            $toDate = $date[1];

            $reports = ($export == false) ? Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::parse($fromDate)->startOfDay()],
                ['created_at', '<=', Carbon::parse($toDate)->endOfDay()]
            ])->with('waiter')->paginate(10) :
            Order::where([
                ['status', '=', 3],
                ['created_at', '>=', Carbon::parse($fromDate)->startOfDay()],
                ['created_at', '<=', Carbon::parse($toDate)->endOfDay()]
            ])->with('waiter')->get();
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
