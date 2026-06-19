<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PlantillaClientesExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles
{
    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return ['Identificacion', 'Nombre', 'Empresa', 'Telefono 1', 'Telefono 2', 'Descripcion'];
    }

    /**
     * Fila(s) de ejemplo para guiar al usuario.
     *
     * @return array<int, array<int, string>>
     */
    public function array(): array
    {
        return [
            ['1020304050', 'Juan Pérez', 'Acme S.A.S.', '3001234567', '6012345678', 'Interesado en página web'],
            ['', '', '', '', '', ''],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
