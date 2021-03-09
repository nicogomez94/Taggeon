<?php

    require_once 'vendor/autoload.php';
    MercadoPago\SDK::setAccessToken("TEST-3003448968088679-030911-ea67e6b52d96b3420b2bf3d04b612be2-725986466");
    
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
    #echo $_POST['transactionAmount']."\n";
    #echo $_POST['token']."\n";
    #echo $_POST['installments']."\n";
    #echo $_POST['paymentMethodId']."\n";
    #echo $_POST['issuer']."\n";
    #echo $_POST['email']."\n";
    #echo $_POST['docType']."\n";
    #echo $_POST['docNumber']."\n";
    #echo $_POST['description']."\n";

    echo json_encode($response);

?>
