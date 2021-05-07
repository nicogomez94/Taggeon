curl -X POST \
-H 'accept: application/json' \
-H 'content-type: application/json' \
-H 'Authorization: Bearer TEST-3352741419059189-050614-b7e70c4e7455bf1a8ffb36f765ba3da9-754982066' \
'https://api.mercadopago.com/checkout/preferences' \
-d '
{
    "items": [
        {
            "id": "item-ID-1234",
            "title": "Mi producto",
            "currency_id": "ARS",
            "picture_url": "https://www.mercadopago.com/org-img/MP3/home/logomp3.gif",
            "description": "Descripción del Item",
            "category_id": "art",
            "quantity": 1,
            "unit_price": 75.76
        }
    ],
    "payer": {
        "name": "Juan",
        "surname": "Lopez",
        "email": "test_user_87614160@testuser.com",
        "phone": {
            "area_code": "11",
            "number": "4444-4444"
        },
        "identification": {
            "type": "DNI",
            "number": "12345678"
        },
        "address": {
            "street_name": "Street",
            "street_number": 123,
            "zip_code": "5700"
        }
    },
    "back_urls": {
        "success": "http://ec2-3-135-36-159.us-east-2.compute.amazonaws.com/pago_success.html",
        "failure": "http://ec2-3-135-36-159.us-east-2.compute.amazonaws.com/pago_failure.html",
        "pending": "http://ec2-3-135-36-159.us-east-2.compute.amazonaws.com/pago_pending.html"
    },
    "auto_return": "approved",
    "payment_methods": {
        "excluded_payment_methods": [
            {
                "id": "master"
	}
        ],
        "excluded_payment_types": [
            {
                "id": "ticket"
            }
        ],
        "installments": 12,
    "notification_url": "http://ec2-3-135-36-159.us-east-2.compute.amazonaws.com/mp/noti.php",
    "statement_descriptor": "MINEGOCIO",
    "external_reference": "riki1234",
    "expires": true,
    "expiration_date_from": "2021-05-06T12:00:00.000-04:00",
    "expiration_date_to": "2021-05-06T19:00:00.000-04:00"
}'
