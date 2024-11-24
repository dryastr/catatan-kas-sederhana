<?php

namespace App\Exports;

use App\Models\Cash;
use App\Models\CashOut;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CashsOutExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @var int
     */
    private $totalCashOut = 0;

    /**
     * @var int
     */
    private $totalBalance = 0;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $cashOutData = CashOut::with('category')->get();


        $this->totalCashOut = $cashOutData->sum('amount');


        $totalCash = Cash::sum('amount');
        $this->totalBalance = $totalCash - $this->totalCashOut;


        $cashOutData->push((object)[
            'id' => null,
            'date' => null,
            'description' => null,
            'category' => (object)['name' => null],
            'notes' => 'Total Kas Keluar',
            'amount' => $this->totalCashOut,
        ]);

        
        $cashOutData->push((object)[
            'id' => null,
            'date' => null,
            'description' => null,
            'category' => (object)['name' => null],
            'notes' => 'Sisa Kas',
            'amount' => $this->totalBalance,
        ]);

        return $cashOutData;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal',
            'Deskripsi',
            'Kategori',
            'Catatan',
            'Jumlah',
        ];
    }

    public function map($cashOut): array
    {
        return [
            $cashOut->id,
            $cashOut->date,
            $cashOut->description,
            optional($cashOut->category)->name,
            $cashOut->notes,
            $this->formatRupiah($cashOut->amount),
        ];
    }

    /**
     * Format angka menjadi format Rupiah.
     *
     * @param float|int $amount
     * @return string
     */
    private function formatRupiah($amount): string
    {
        return 'Rp. ' . number_format($amount, 2, ',', '.');
    }
}
