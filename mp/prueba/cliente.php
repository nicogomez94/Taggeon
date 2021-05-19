
<?php
require_once '../vendor/autoload.php';

  MercadoPago\SDK::setAccessToken("TEST-3352741419059189-050614-d79d34f25bad98948442d95c54352e3b-754221997");

  $customer = new MercadoPago\Customer();
  $customer->email = "test_user_87614160@testuser.com";
  $customer->save();
  echo "fin";

#  $card = new MercadoPago\Card();
#  $card->token = "9b2d63e00d66a8c721607214cedaecda";
#  $card->customer_id = $customer->id();
#  $card->save();

?>



