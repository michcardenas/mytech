<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ClientesImport implements ToCollection
{
    public Collection $filas;

    public function __construct()
    {
        $this->filas = collect();
    }

    public function collection(Collection $rows): void
    {
        $this->filas = $rows;
    }
}
