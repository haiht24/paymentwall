<?php

namespace App\models;

include('models/User.php');

$email = 'haiht369@gmail.com';
$pwd = 'haiht';
$user = new User($email, $pwd);
if($user->login()){
    $paymentStatus = $user->payment(10000, 'vnd', 'creditCard', []);
    echo "<pre>";var_dump($paymentStatus);die;
}else{
    die('Login error');
}
