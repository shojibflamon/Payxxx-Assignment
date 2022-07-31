<?php

namespace Shojibflamon\PayseraAssignment\Calculation;

use Shojibflamon\PayseraAssignment\Model\Amount;
use Shojibflamon\PayseraAssignment\Model\Currency;
use Shojibflamon\PayseraAssignment\Model\DateOperation;
use Shojibflamon\PayseraAssignment\Helper\Dump;
use Shojibflamon\PayseraAssignment\Model\OperationType;
use Shojibflamon\PayseraAssignment\Model\User;
use Shojibflamon\PayseraAssignment\Provider\PayseraExchangeRateServiceProvider;
use Shojibflamon\PayseraAssignment\Service\CurrencyConverter;
use Shojibflamon\PayseraAssignment\Service\FileProcessInterface;

class CalculateCommission
{

    use Dump;


    public const OPERATION_TYPE_PRIVATE_WITHDRAW_RATE = 0.3;



    private $date;
    private $fileProcess;
    private array $commissionFees;
    private array $transactions;
    private $commissionFee;
    private $withdrawLimit;
    private $creditLimit;
    private $arrayDb;

    private $deposit;
    private $businessWithdraw;
    private $privateWithdraw;


    public function __construct(FileProcessInterface $fileProcess)
    {
        $this->fileProcess = $fileProcess;
        $this->commissionFee = 0;
        $this->withdrawLimit = 3;
        $this->creditLimit = 1000;
        $this->commissionFees = [];
        $this->arrayDb = [];
        $this->transactions = $this->fileProcess->transactions;
        $this->deposit = new Deposit();
        $this->businessWithdraw = new BusinessWithdraw();
        $this->privateWithdraw = new PrivateWithdraw();

    }

    public function process(): array
    {
        foreach ($this->transactions as $key => $transaction) {

            /*
             * import Deposit
             * import WithdrawForBusiness
             * import WithdrawForPrivate
             *
             * CalculateCommission(transaction)
             *  - check stretgy(transaction)
             *  - calcuclate(transaction) : CommissionInterface
             *  -- depending on stretgy call this->deposite->calculate
             *  -- depending on stretgy call this->WithdrawForBusiness->calculate
             *  -- depending on stretgy call this->WithdrawForPrivate->calculate
             *
             *
             * CommissionFeeInterface
             *  - calculate(Transaction $transaction)
             *
             *  - IMP Deposit
             *      - constructor()
             *      - calculate(transaction) > getAmount > operation currency > convert > Rate > celling
             *  - IMP WithdrawForBusiness
             *      - constructor()
             *      - calculate(transaction) > getAmount > operation currency > convert > Rate > celling
             *  - IMP WithdrawForPrivate
             *      - constructor()
             *      - calculate(transaction)
             *          > getAmount > getUser()->getUserId > numberOfWeek > array_key_exist
             *          > commissionableAmount > withdrawLimit > creditLimit > store_in_array > reverse_convert > Rate > cellling
             *
             *
             *
             *
             * */

//            if (in_array($key,[4,5,6,7,8,9,10,11,12])) {
//                continue;
//            }

            $this->resetCredentials();

//            $operationAmount = $transaction->getAmount()->getAmount();
//            $operationCurrency = $transaction->getAmount()->getOperationCurrency();
//            $baseCurrency = $transaction->getAmount()->getBaseCurrency();

//            $amountInEuro = $transaction->getAmount()->getCurrencyConverter()->convert($operationAmount, $operationCurrency, $baseCurrency);
//            self::ddd($transaction);

            if ($this->isDepositAction($transaction)) {
//                $this->commissionFee = $amountInEuro * self::OPERATION_TYPE_DEPOSIT_RATE * .01;
//                return $this->deposit->calculate($transaction);
                $this->commissionFee = $this->deposit->calculate($transaction);
            }




            if ($this->isBusinessWithdrawAction($transaction)) {
//                $this->commissionFee = $amountInEuro * self::OPERATION_TYPE_BUSINESS_WITHDRAW_RATE * .01;
                $this->commissionFee = $this->businessWithdraw->calculate($transaction);
            }




            if ($this->isPrivateWithdrawAction($transaction)) {

                $this->commissionFee = $this->privateWithdraw->calculate($transaction);

//
//                $weekStartDate = $transaction->getDateOperation()->getFirstDayOfWeek();
//
//                $userId = $transaction->getUser()->getUserId();
//
//                if (array_key_exists($userId, $this->arrayDb) && array_key_exists($weekStartDate, $this->arrayDb[$userId])) {
//                    $lastTransaction = $this->arrayDb[$userId][$weekStartDate];
//                    $this->creditLimit = $lastTransaction['creditLimit'];
//                    $this->withdrawLimit = $lastTransaction['withdrawLimit'];
//                }
//
//                if ($amountInEuro < $this->creditLimit) {
//                    $exceededAmount = $commissionableAmount = 0;
//                    $this->creditLimit -= $amountInEuro;
//                } else {
//                    $commissionableAmount = $exceededAmount = $amountInEuro - $this->creditLimit;
//                    $this->creditLimit = 0;
//                }
//
//                $this->decreaseWithdrawLimit();
//
//                $this->arrayDb[$userId][$weekStartDate] = [
//                    'exceededAmount' => $exceededAmount,
//                    'commissionableAmount' => $commissionableAmount,
//                    'creditLimit' => $this->creditLimit,
//                    'withdrawLimit' => $this->withdrawLimit,
//                ];
//            self::dd($this->arrayDb[$userId]);
//
//                if ($transaction->getCurrency()->getCode() !== 'EUR') {
//                    $commissionableAmount = $transaction->getAmount()->getCurrencyConverter()->convert($commissionableAmount, $baseCurrency, $operationCurrency);
//                }
//
//                $this->commissionFee = $commissionableAmount * self::OPERATION_TYPE_PRIVATE_WITHDRAW_RATE * .01;

//

            }
//            self::dd($this->commissionFee);
            if ($transaction->getCurrency()->getCode() === 'JPY') {
                $this->commissionFee = $transaction->getAmount()->ceiling($this->commissionFee, 0);
            } else {
                $this->commissionFee = $transaction->getAmount()->ceiling($this->commissionFee, 2);
            }

            $this->commissionFees[] = $this->commissionFee;

        }

        return $this->commissionFees;
    }

    private function isDepositAction($transaction): bool
    {
        return $transaction->getOperationType()->getOperationType() === OperationType::OPERATION_TYPE_DEPOSIT;
    }

    private function isBusinessWithdrawAction($transaction): bool
    {
        return $transaction->getOperationType()->getOperationType() === OperationType::OPERATION_TYPE_WITHDRAW &&
            $transaction->getUser()->getUserType() === User::USER_TYPE_BUSINESS;
    }

    private function isPrivateWithdrawAction($transaction): bool
    {
        return $transaction->getOperationType()->getOperationType() === OperationType::OPERATION_TYPE_WITHDRAW &&
            $transaction->getUser()->getUserType() === User::USER_TYPE_PRIVATE;
    }


    private function resetCredentials(): void
    {
        $this->commissionFee = 0;
        $this->withdrawLimit = 3;
        $this->creditLimit = 1000;
    }

    private function decreaseWithdrawLimit(): void
    {
        if ($this->withdrawLimit > 0) {
            $this->withdrawLimit--;
        }
    }


    public function _process(): array
    {
        foreach ($this->transactions as $key => $transaction) {

            if ($key !== 3) {
                continue;
            }

            $commissionFee = 0;
            $this->withdrawLimit = 0;
            $this->creditLimit = 1000;

            list($operationDate, $userId, $userType, $operationType, $operationAmount, $operationCurrency) = $transaction;

            $operationAmount = new Amount($operationAmount);

            // all currency convert to EUR
            $eur = new Currency('EUR');

            $currency = new Currency($operationCurrency);
            $payseraServiceProvider = new PayseraExchangeRateServiceProvider();
            $exchangeRate = $payseraServiceProvider->setExchangeRateSource('static')->getExchangeRate($currency, $eur);

            $currencyConverter = new CurrencyConverter($exchangeRate);

            $amountInEuro = $currencyConverter->convert($operationAmount->getAmount(), $currency, $eur);

            if ($operationType === self::OPERATION_TYPE_DEPOSIT) {
                $commissionFee = $operationAmount->getAmount() * self::OPERATION_TYPE_DEPOSIT_RATE * .01;
            }

            if ($operationType === self::OPERATION_TYPE_WITHDRAW && $userType === self::USER_TYPE_BUSINESS) {
                $commissionFee = $operationAmount->getAmount() * self::OPERATION_TYPE_BUSINESS_WITHDRAW_RATE * .01;
            }

            if ($operationType === self::OPERATION_TYPE_WITHDRAW && $userType === self::USER_TYPE_PRIVATE) {

                $this->date = new DateOperation($operationDate);
                $weekStartDate = $this->date->getFirstDayOfWeek();


                if (array_key_exists($userId, $this->arrayDb)) {
                    if (array_key_exists($weekStartDate, $this->arrayDb[$userId])) {
                        $lastTransaction = $this->arrayDb[$userId][$weekStartDate];
                        $this->creditLimit = $lastTransaction['creditLimit'];
                        $this->withdrawLimit = $lastTransaction['withdrawLimit'];
                    }
                }


                if ($amountInEuro >= $this->creditLimit) {
                    $exceededAmount = $amountInEuro - $this->creditLimit;
                    $commissionableAmount = $exceededAmount;
                    $this->creditLimit = 0;
                } else {
                    $exceededAmount = 0;
                    $commissionableAmount = 0;
                    $this->creditLimit -= $amountInEuro;
                }

                if ($this->withdrawLimit > 0) {
                    $this->withdrawLimit--;
                }

                $this->arrayDb[$userId][$weekStartDate] = [
                    'exceededAmount' => $exceededAmount,
                    'commissionableAmount' => $commissionableAmount,
                    'creditLimit' => $this->creditLimit,
                    'withdrawLimit' => $this->withdrawLimit,
                ];


                if ($operationCurrency !== 'EUR') {
                    $exchangeRate = $payseraServiceProvider->setExchangeRateSource('static')->getExchangeRate($eur, $currency);
                    $currencyConverter = new CurrencyConverter($exchangeRate);
                    $commissionableAmount = $currencyConverter->convert($commissionableAmount, $eur, $currency);

                }

                $commissionFee = $commissionableAmount * self::OPERATION_TYPE_PRIVATE_WITHDRAW_RATE * .01;

            }

            if ($operationCurrency === 'JPY') {
                $commissionFee = $operationAmount->ceiling($commissionFee, 0);
            } else {
                $commissionFee = $operationAmount->ceiling($commissionFee, 5);
            }

            $this->commissionFees[] = $commissionFee;

        }

        return $this->commissionFees;
    }

}