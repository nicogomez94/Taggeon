<?php
    require_once 'vendor/autoload.php';
    
    $items = array();
    MercadoPago\SDK::setAccessToken("TEST-2892726168082494-032501-ab7e4692f39158920f69b50fcdb4177e-594511111");

    // Crea un ítem en la preferencia
    $item = new MercadoPago\Item();
    $item->title = $_GET["title"];
    $item->quantity = 1;
    $item->unit_price = $_GET["price"];
    $item->descripion = $_GET["title"];
    $item->currency_id = "ARS";
    array_push($items, $item);

    //Creamos un payment
    $payment = new MercadoPago\Payment();
    $payment->transaction_amount = $_GET["price"];
    $payment->description = $_GET["title"];
    $payment->installments = 1;
    $payment->live_mode = true;

    // Crea un objeto de preferencia
    $preference = new MercadoPago\Preference();
    $preference->items = $items;
    $preference->auto_return = "approved";
    $preference->payments = array($payment);

    $preference->back_urls = array(
        'failure' => "",
        'pending' => "",
        'success' => "http://www.taggeon.com/mis-compras.html" // con colocar este es mas que suficiente
    );

    $preference->binary_mode = true;
    $preference->notification_url = "http://www.taggeon.com/mp/notification-mp.php"; //para escuchar el evento de pago y validarlo

    //Datos que puedes enviar para guardar en el pago y usarlo en el webhook
    //para comprobar lo que quieras id usuario id etc etc
    $metadata = array(
        "id_carrito" => $_GET["id"]
    );
    $preference->metadata = $metadata;
    $preference->save();

    header('Location: '.$preference->init_point);

?>
