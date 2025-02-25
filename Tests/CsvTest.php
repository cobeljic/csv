<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Vozdjn\Csv\Csv;

class CsvTest extends TestCase
{

    function test_it_loads_csv_file()
    {
        $path = __DIR__ . '/stubs/small_test.csv';

        $expected = file_get_contents($path);

        $this->assertEquals($expected, Csv::load($path));
    }

    function test_is_header_and_rows_column_count_equal()
    {
        $path = __DIR__ . '/stubs/small_test.csv';

        $csv = Csv::load($path);

        $this->assertTrue($csv->validate());
    }
}