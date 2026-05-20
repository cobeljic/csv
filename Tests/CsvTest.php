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

    function test_it_does_not_repeat_header_when_appending_to_existing_file()
    {
        $testFile = __DIR__ . '/stubs/append_test.csv';
        if (file_exists($testFile)) {
            unlink($testFile);
        }

        $header = ['id', 'name'];
        $data1 = [['1', 'John']];
        $data2 = [['2', 'Jane']];

        // First append - should write header
        Csv::append($testFile, $header, $data1);

        // Second append - should NOT write header
        Csv::append($testFile, $header, $data2);

        $content = file_get_contents($testFile);
        $lines = array_filter(explode("\n", trim($content)));

        $this->assertEquals("id,name\n1,John\n2,Jane", trim($content));
        $this->assertCount(3, $lines);

        unlink($testFile);
    }
}