<?php

namespace App\models;
class Payment {

    protected $amount;
    protected $commission;
    protected $currency;
    protected $paymentMethod;
    protected $userId;
    protected $arrBankInfo;
    protected $transactionStatus;

    public function __construct($userId, $amount, $currency, $paymentMethod, $arrBankInfo) {
        $this->commission = 1; // Get 1% of amount
        $this->userId = $userId;
        $this->currency = $currency;
        $this->paymentMethod = $paymentMethod;
        $this->arrBankInfo = $arrBankInfo;

        $amount = $amount + ($amount * $this->commission) / 100;
        $this->amount = $amount;
    }

    public function payment() {
        $status = null;
        // send card info to API check
        if ($this->paymentMethod === 'creditCard') {
            $status = $this->payByCreditCard($this->arrBankInfo, $this->amount, $this->currency);
        } else if ($this->paymentMethod === 'bankTransfer') {
            $status = $this->payByBankTransfer($this->arrBankInfo, $this->amount, $this->currency);
        }
        $this->transactionStatus = $status;
        $this->logTransaction();
        return $status;
    }

    // Call to API check valid card info and do payment
    private function payByCreditCard($cardInfo, $amount, $currency) {
        // if success
        return true;
        // else
        // return false;
    }

    // Call to API valid bank info
    private function payByBankTransfer($cardInfo, $amount, $currency) {
        // if success
        return true;
        // else
        // return false;
    }

    private function logTransaction() {
        $now = date("Y-m-d H:i:s");
        // Save log to json file
        $log = [
            'userId' => $this->userId,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'paymentMethod' => $this->paymentMethod,
            'status' => $this->transactionStatus,
            'createdDate' => $now
        ];
        $file = './logs/transaction_log.json';
        if(file_exists($file)){
            $jsonLogs = json_decode(file_get_contents($file));
            $jsonLogs = $jsonLogs ? $jsonLogs : [];
            array_push($jsonLogs, $log);
            $jsonLogs = json_encode($jsonLogs);
            file_put_contents($file, $jsonLogs);
        }else{
            die('file not exist');
        }

    }
}