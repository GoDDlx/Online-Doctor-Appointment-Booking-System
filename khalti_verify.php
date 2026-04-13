<?php
// khalti_verify.php
$data = json_decode(file_get_contents("php://input"), true);

if(!$data) { echo "fail"; exit; }

$token = $data['token'];
$amount = $data['amount'];

$secret_key = "test_secret_key_xxxxx"; // Replace with your secret key

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://khalti.com/api/v2/payment/verify/");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['token'=>$token,'amount'=>$amount]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Key $secret_key"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$res = json_decode($response, true);

if(isset($res['idx'])){
    echo "success";
} else {
    echo "fail";
}