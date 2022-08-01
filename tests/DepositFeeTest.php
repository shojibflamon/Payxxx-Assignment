<?php

namespace Shojibflamon\Tests;

use PHPUnit\Framework\TestCase;
use Shojibflamon\PayseraAssignment\Calculation\CalculateCommission;
use Shojibflamon\PayseraAssignment\Service\CsvFile;
use Shojibflamon\PayseraAssignment\Service\CsvFileProcess;
use Shojibflamon\PayseraAssignment\Service\CsvFileProcessNew;
use Shojibflamon\PayseraAssignment\Service\FileData;

class DepositFeeTest extends TestCase
{
    public function testDepositFee()
    {
        $file = __DIR__. '/../input.csv';
        $csv = new CsvFile($file);
        $transactions = $csv->getData();
        $processFile = new CsvFileProcessNew($transactions);
//        $processFile = new CsvFileProcess($file);
        $transaction = $processFile->convertObject();
        $calculateCommission = new CalculateCommission($transaction);
        $fees = $calculateCommission->process();

        $this->assertEquals(['0.60', '3.00', '0.00', '0.06', '1.50', '0', '0.70', '0.30', '0.30', '3.00', '0.00', '0.00', '8612'], $fees);
    }


    public function testDepositFeeIndividual()
    {
        $input = [
            '2014-12-31,4,private,withdraw,1200.00,EUR',
            '2015-01-01,4,private,withdraw,1000.00,EUR',
            '2016-01-05,4,private,withdraw,1000.00,EUR',
            '2016-01-05,1,private,deposit,200.00,EUR',
        ];

        $transactions = new FileData($input);
        $processFile = new CsvFileProcessNew($transactions);
        $transaction = $processFile->convertObject();
        $calculateCommission = new CalculateCommission($transaction);
        $fees = $calculateCommission->process();

        $this->assertEquals(['0.60', '3.00', '0.00', '0.06'], $fees);
    }

    public function testDepositFeeIndividualAgain()
    {
        $input = [
            '2016-02-19,5,private,withdraw,3000000,JPY',
        ];

        $transactions = new FileData($input);
        $processFile = new CsvFileProcessNew($transactions);
        $transaction = $processFile->convertObject();
        $calculateCommission = new CalculateCommission($transaction);
        $fees = $calculateCommission->process();

        $this->assertEquals(['8612'], $fees);
    }

}