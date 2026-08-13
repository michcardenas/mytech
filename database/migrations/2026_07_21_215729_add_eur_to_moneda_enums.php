<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * @var array<int, array{table:string, column:string, default:?string}>
     */
    private array $columns = [
        ['table' => 'internal_projects', 'column' => 'moneda', 'default' => 'COP'],
        ['table' => 'internal_projects', 'column' => 'desarrollador_moneda', 'default' => 'COP'],
        ['table' => 'developer_payments', 'column' => 'moneda', 'default' => 'COP'],
        ['table' => 'gestion_payments', 'column' => 'moneda', 'default' => 'COP'],
        ['table' => 'project_expenses', 'column' => 'moneda', 'default' => 'COP'],
        ['table' => 'developers', 'column' => 'moneda_default', 'default' => 'COP'],
    ];

    public function up(): void
    {
        // Solo MySQL/MariaDB usan ENUM nativo. En sqlite (tests) la columna es TEXT y ya admite 'EUR'.
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach ($this->columns as $col) {
            $default = $col['default'] ? "DEFAULT '{$col['default']}'" : '';
            DB::statement("ALTER TABLE `{$col['table']}` MODIFY `{$col['column']}` ENUM('COP','USD','EUR') {$default}");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach ($this->columns as $col) {
            DB::statement("UPDATE `{$col['table']}` SET `{$col['column']}` = 'COP' WHERE `{$col['column']}` = 'EUR'");
            $default = $col['default'] ? "DEFAULT '{$col['default']}'" : '';
            DB::statement("ALTER TABLE `{$col['table']}` MODIFY `{$col['column']}` ENUM('COP','USD') {$default}");
        }
    }
};
