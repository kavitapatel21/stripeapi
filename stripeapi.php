<?php

$email = "test78@gmail.com";
$name = "test7";
//Create token api START

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.stripe.com/v1/tokens',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'card%5Bnumber%5D=4242424242424242&card%5Bexp_month%5D=12&card%5Bexp_year%5D=2034&card%5Bcvc%5D=123',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer sk_test_51KMnalGSLZI5qKJB7ANMSnomf1xE8BcIIBotP1mMtpnpiCj0DMVVhlBrLDX3Eg20CURzSjUcR9GhTn0NTbNwbcxG00syBBR6Wi',
    'Content-Type: application/x-www-form-urlencoded'
  ),
));

$response = curl_exec($curl);
curl_close($curl);
//echo $response;
$result = json_decode($response, TRUE);
//print_r($result);
$token_id = $result['id'];

//Create token api END

//Curl start for check customer already exist or not
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.stripe.com/v1/customers',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_POSTFIELDS => 'email=' . $email . '',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer sk_test_51KMnalGSLZI5qKJB7ANMSnomf1xE8BcIIBotP1mMtpnpiCj0DMVVhlBrLDX3Eg20CURzSjUcR9GhTn0NTbNwbcxG00syBBR6Wi',
    'Content-Type: application/x-www-form-urlencoded'
  ),
));
$response = curl_exec($curl);
curl_close($curl);
$result = json_decode($response, TRUE);
//echo '<pre>';
//print_r($result);
//echo $result['data'][0]['id'];
//die;
if ($result['data']) {
  $customer_id = $result['data'][0]['id'];
  echo 'customer already exist';
  //echo $customer_id;

  //Update Customer START
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.stripe.com/v1/customers/' . $customer_id . '',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'source=' . $token_id . '',
    CURLOPT_HTTPHEADER => array(
      'Authorization: Bearer sk_test_51KMnalGSLZI5qKJB7ANMSnomf1xE8BcIIBotP1mMtpnpiCj0DMVVhlBrLDX3Eg20CURzSjUcR9GhTn0NTbNwbcxG00syBBR6Wi',
      'Content-Type: application/x-www-form-urlencoded'
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  //echo $response;
  //echo "updated";

  //Update customer END
} else {
  //Create customer START
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.stripe.com/v1/customers',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'name=' . $name . '&email=' . $email . '&source=' . $token_id . '',
    CURLOPT_HTTPHEADER => array(
      'Authorization: Bearer sk_test_51KMnalGSLZI5qKJB7ANMSnomf1xE8BcIIBotP1mMtpnpiCj0DMVVhlBrLDX3Eg20CURzSjUcR9GhTn0NTbNwbcxG00syBBR6Wi',
      'Content-Type: application/x-www-form-urlencoded'
    ),
  ));

  $response = curl_exec($curl);
  curl_close($curl);
  //echo $response;
  $result = json_decode($response, TRUE);
  $customer_id = $result['id'];
  //Create Customer END	


  //Charge api START
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.stripe.com/v1/charges',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'amount=2000&currency=usd&description=test&customer=' . $customer_id . '',
    CURLOPT_HTTPHEADER => array(
      'Authorization: Bearer sk_test_51KMnalGSLZI5qKJB7ANMSnomf1xE8BcIIBotP1mMtpnpiCj0DMVVhlBrLDX3Eg20CURzSjUcR9GhTn0NTbNwbcxG00syBBR6Wi',
      'Content-Type: application/x-www-form-urlencoded'
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  echo $response;
  //Charge api END
  
}
