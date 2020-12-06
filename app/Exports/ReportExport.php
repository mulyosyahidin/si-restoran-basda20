<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class ReportExport implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithMapping
{
    private $reports = [];

    public function __construct($reports)
    {
        $this->reports = $reports;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->reports;
    }

    public function headings(): array
    {
        return [
            '#',
            'ID',
            'Waiter',
            'Pelanggan',
            'Tanggal',
            'Jumlah Item',
            'Total Harga'
        ];
    }

    public function map($reports): array
    {
        return [
            $reports->id,
            '#' . $reports->order_number,
            $reports->waiter->name,
            $reports->customer_name,
            Carbon::parse($reports->created_at)->isoFormat('dddd, DD MMM YYYY HH:mm'),
            $reports->total_item,
            'Rp ' . number_format($reports->total_price, 2, ',', '.')
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:G1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFFFF00');

                $event->sheet->getStyle('A1:G1')->getFont()->setBold(true);
            }
        ];
    }
}
