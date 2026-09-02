<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PlantillaBolsaHorasExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles
{
    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return ['Fecha', 'Tema', 'Descripcion', 'Cantidad', 'Unidad'];
    }

    /**
     * Filas de ejemplo para guiar al usuario. La columna Unidad acepta "horas" o "minutos".
     *
     * @return array<int, array<int, string>>
     */
    public function array(): array
    {
        return [
            ['2026-09-01', 'Checkout', 'Ajustes en el checkout y pruebas', '2,5', 'horas'],
            ['2026-09-02', 'Soporte', 'Llamada de soporte y correcciones', '45', 'minutos'],
            ['', '', '', '', ''],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
