<?php

namespace App\models;
require('Payment.php');

class User {

    protected $user;
    protected $userId;
    protected $email;
    protected $pwd;
    protected $table;
    protected $paymentMethod = ['creditCard', 'bankTransfer'];
    protected $paymentHistory;

    public function __construct($email, $pwd) {
        $this->email = $email;
        $this->pwd = $pwd;
        $this->table = 'database/user.json';
    }

    public function payment($amount, $currency, $paymentMethod, $bankInfo) {
        // require user logged in
        if (!empty($this->userId)) {
            $pm = new Payment($this->userId, $amount, $currency, $paymentMethod, $bankInfo);
            $paymentStatus = $pm->payment();
            return ['status' => $paymentStatus];
        }
        return ['status' => 'error', 'message' => 'User not logged in'];
    }

    public function login() {
        $user = $this->checkCredentials();
        if ($user) {
            $this->user = $user;
            $_SESSION['userId'] = $user->id;
            $this->userId = $user->id;
            return $user->id;
        }
        return false;
    }

    protected function checkCredentials() {
        $email = $this->email;
        $pwd = md5($this->pwd);

        if (file_exists($this->table)) {
            $data = json_decode(file_get_contents($this->table));
            if(count($data) > 0){
                foreach ($data as $datum) {
                    if($datum->email === $email){
                        if($datum->pwd === $pwd){
                            return $datum;
                        }
                    }
                }
                return null;
            }
        } else {
            die('file not exist');
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getPaymentMethod() {
        return $this->paymentMethod;
    }

    /**
     * @param mixed $paymentMethod
     */
    public function setPaymentMethod($paymentMethod = []) {
        $this->paymentMethod = $paymentMethod;
    }

    public function removePaymentMethod($paymentMethod) {
        // remove this method from this user
        if (!empty($this->user)) {

        }
    }

    /**
     * @return mixed
     */
    public function getPaymentHistory($paymentMethod = null) {
        $f = './logs/transaction_log.json';
        $found = [];
        if (!empty($paymentMethod)) {
            // Get payment history of this user by input payment method
            if (file_exists($f)) {
                $data = json_decode(file_get_contents($f));
                if(count($data) > 0){
                    foreach ($data as $datum) {
                        if($datum->userId === $this->userId){
                            if($datum->paymentMethod === $this->paymentMethod){
                                array_push($found, $datum);
                            }
                        }
                    }
                }
            } else {
                die('file not exist');
            }
        } else {
            // Get all payment history
            if (file_exists($f)) {
                $data = json_decode(file_get_contents($f));
                if(count($data) > 0){
                    foreach ($data as $datum) {
                        if($datum->userId === $this->userId){
                            array_push($found, $datum);
                        }
                    }
                }
            } else {
                die('file not exist');
            }
        }
        return $found;
    }
}