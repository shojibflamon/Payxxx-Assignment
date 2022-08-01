<?php

namespace Shojibflamon\Tests;

use PHPUnit\Framework\TestCase;
use Shojibflamon\PayseraAssignment\Calculation\DepositFee;
use Shojibflamon\PayseraAssignment\Model\Amount;
use Shojibflamon\PayseraAssignment\Model\DateOperation;
use Shojibflamon\PayseraAssignment\Model\OperationType;
use Shojibflamon\PayseraAssignment\Model\Transaction;
use Shojibflamon\PayseraAssignment\Model\User;

class DepositFeeTest extends TestCase
{
    /**
     * @return void
     */
    public function testDepositFeeForBusinessClientEuro(): void
    {
        $date = new DateOperation('2016-01-10');
        $user = new User(2, 'business');
        $amount = new Amount(10000, 'EUR');
        $operationType = new OperationType('deposit');
        $transactionObj = new Transaction($date, $user, $operationType, $amount);
        $fee = (new DepositFee())->calculate($transactionObj);
        $fee = $transactionObj->getAmount()->ceiling($fee, 2);

        $this->assertEquals('3.00', $fee);
    }

    /**
     * @return void
     */
    public function testDepositFeeForPrivateClientEuro(): void
    {
        $date = new DateOperation('2016-01-05');
        $user = new User(1, 'private');
        $amount = new Amount(200, 'EUR');
        $operationType = new OperationType('deposit');
        $transactionObj = new Transaction($date, $user, $operationType, $amount);
        $fee = (new DepositFee())->calculate($transactionObj);
        $fee = $transactionObj->getAmount()->ceiling($fee, 2);

        $this->assertEquals('0.06', $fee);
    }

    /**
     * @return void
     */
    public function testDepositFeeForPrivateClientJpy(): void
    {
        $date = new DateOperation('2016-02-19');
        $user = new User(1, 'private');
        $amount = new Amount(3000000, 'JPY');
        $operationType = new OperationType('deposit');
        $transactionObj = new Transaction($date, $user, $operationType, $amount);
        $fee = (new DepositFee())->calculate($transactionObj);
        $fee = $transactionObj->getAmount()->ceiling($fee, 0);

        $this->assertEquals('7', $fee);
    }

}