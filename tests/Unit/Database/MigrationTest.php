<?php

namespace Tests\Unit\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_expected_tables_exist()
    {
        $tables = [
            'users',
            'roles',
            'students',
            'teachers',
            'filieres',
            'classes',
            'modules',
            'notes',
            'absences',
            'payments'
        ];

        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Table {$table} does not exist.");
        }
    }
}
