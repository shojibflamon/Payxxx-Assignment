<?php

namespace Shojibflamon\Tests;

use PHPUnit\Framework\TestCase;
use Shojibflamon\PayseraAssignment\Calculation\CalculateCommission;
use Shojibflamon\PayseraAssignment\Service\CsvFileProcess;

class DemoTest extends TestCase
{
    /*public function testCommissionFee()
    {
        $file = __DIR__. '/../input.csv';
        $processFile = new CsvFileProcess($file);
        $transaction = $processFile->convertObject();
        $calculateCommission = new CalculateCommission($transaction);
        $fees = $calculateCommission->process();

        $this->assertEquals(['0.60', '3.00', '0.00', '0.06', '1.50', '0', '0.70', '0.30', '0.30', '3.00', '0.00', '0.00', '8612'], $fees);
    }*/
}

