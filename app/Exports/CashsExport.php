<?php

namespace App\Exports;

use App\Models\Cash;
use App\Models\CashOut;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CashsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @var int
     */
    private $totalAmount = 0;

    /**
     * @var int
     */
    private $totalBalance = 0;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $cashData = Cash::with('category')->get();


        $this->totalAmount = $cashData->sum('amount');


        $totalCashOut = CashOut::sum('amount');
        $this->totalBalance = $this->totalAmount - $totalCashOut;


        $cashData->push((object)[
            'id' => null,
            'date' => null,
            'description' => null,
            'category' => (object)['name' => null],
            'notes' => 'Total Kas Masuk',
            'amount' => $this->totalAmount,
        ]);


        $cashData->push((object)[
            'id' => null,
            'date' => null,
            'description' => null,
            'category' => (object)['name' => null],
            'notes' => 'Sisa Kas',
            'amount' => $this->totalBalance,
        ]);

        return $cashData;
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

    public function map($cash): array
    {
        return [
            $cash->id,
            $cash->date,
            $cash->description,
            optional($cash->category)->name,
            $cash->notes,
            $this->formatRupiah($cash->amount),
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
