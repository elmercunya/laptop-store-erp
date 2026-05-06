<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
        public function collection()
    {
        return Sale::with('client')->where('status', 'COMPLETADA')->get();
    }

    public function headings(): array {
        return [
            'ID de venta',
            'Fecha',
            'Cliente',
            'Total (S/)',
            'Estado'
        ];
    }

    public function map($sale):array {
        return [
            $sale->id,
            $sale->created_at->format('d/m/Y H:i'),
            $sale->client->name,
            $sale->total,
            'Completada'
        ];
    }
}
