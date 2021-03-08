curl -X POST \
-H "Content-Type: application/json" \
"http://ec2-3-135-36-159.us-east-2.compute.amazonaws.com/mp/process_payment.php?email=test%40test.com&docType=DNI&docNumber=12333444&issuer=288&installments=1&transactionAmount=100&paymentMethodId=visa&description=xxx&token=48705f65d674894d17802c5e2f0753d9"
