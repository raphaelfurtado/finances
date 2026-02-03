<?php

namespace Tests\Unit\Services;

use App\Services\ImportService;
use Tests\TestCase;
use Illuminate\Support\Carbon;

class ImportServiceTest extends TestCase
{
    public function test_it_can_parse_csv_transaction()
    {
        $service = new ImportService();

        // Create a temporary CSV file
        $content = "date,category,title,amount\n2023-10-05,Transporte,Uber,29.90";
        $filePath = sys_get_temp_dir() . '/test.csv';
        file_put_contents($filePath, $content);

        $transactions = $service->parseCsv($filePath);

        $this->assertCount(1, $transactions);
        $this->assertEquals(29.90, $transactions[0]['amount']);
        $this->assertEquals('Uber', $transactions[0]['description']);
        $this->assertEquals('expense', $transactions[0]['type']);

        unlink($filePath);
    }
}
