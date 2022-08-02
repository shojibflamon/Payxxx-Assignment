<?php

namespace Shojibflamon\Tests;

use PHPUnit\Framework\TestCase;
use Shojibflamon\PayxxxxAssignment\Calculation\CalculateCommission;
use Shojibflamon\PayxxxxAssignment\Service\CsvFile;
use Shojibflamon\PayxxxxAssignment\Service\CsvFileProcess;
use Shojibflamon\PayxxxxAssignment\Service\FileData;

class DemoTest extends TestCase
{
    /**
     * @return void
     */
    public function testCommissionFeeFromCsvFile(): void
    {
        $file = __DIR__ . '/../input.csv';
        $csv = new CsvFile($file);
        $transactions = $csv->getData();
        $processFile = new CsvFileProcess($transactions);
        $transactionFactory = $processFile->parseStringFromCsv()->transformation();
        $calculateCommission = new CalculateCommission($transactionFactory);
        $fees = $calculateCommission->process();

        $this->assertEquals(['0.60', '3.00', '0.00', '0.06', '1.50', '0', '0.70', '0.30', '0.30', '3.00', '0.00', '0.00', '8612'], $fees);
    }

    /**
     * @return void
     */
    public function testCommissionFeeFromArray(): void
    {
        $input = [
            '2014-12-31,4,private,withdraw,1200.00,EUR',
            '2015-01-01,4,private,withdraw,1000.00,EUR',
            '2016-01-05,4,private,withdraw,1000.00,EUR',
            '2016-01-05,1,private,deposit,200.00,EUR',
        ];

        $transactions = new FileData($input);
        $processFile = new CsvFileProcess($transactions);
        $transactionFactory = $processFile->parseStringFromCsv()->transformation();
        $calculateCommission = new CalculateCommission($transactionFactory);
        $fees = $calculateCommission->process();

        $this->assertEquals(['0.60', '3.00', '0.00', '0.06'], $fees);
    }
}

