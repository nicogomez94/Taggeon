<?php

    require_once 'vendor/autoload.php';
    MercadoPago\SDK::setAccessToken("TEST-8374534224864099-050502-c525c4cf6fba42fba3060c7e2f9f82e9-754216372");
    
    $payment = new MercadoPago\Payment();
    $payment->transaction_amount = (float)$_POST['transactionAmount'];
    $payment->token = $_POST['token'];
    $payment->description = $_POST['description'];
    $payment->installments = (int)$_POST['installments'];
    $payment->payment_method_id = $_POST['paymentMethodId'];
    $payment->issuer_id = (int)$_POST['issuer'];
        
    $payer = new MercadoPago\Payer();
    $payer->email = $_POST['email'];
    $payer->identification = array(
        "type" => $_POST['docType'],
        "number" => $_POST['docNumber']
    );
    $payment->payer = $payer;

    $payment->save();

    $response = array(
        'status' => $payment->status,
        'status_detail' => $payment->status_detail,
        'id' => $payment->id
    );
    echo $_POST['transactionAmount']."\n";
    echo $_POST['token']."\n";
    echo $_POST['installments']."\n";
    echo $_POST['paymentMethodId']."\n";
    echo $_POST['issuer']."\n";
    echo $_POST['email']."\n";
    echo $_POST['docType']."\n";
    echo $_POST['docNumber']."\n";
    echo $_POST['description']."\n";

    echo json_encode($response);

?>
