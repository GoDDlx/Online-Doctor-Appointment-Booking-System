<?php
session_start();

$amount = 100; // Rs
$tax_amount = 0;
$total_amount = $amount + $tax_amount;
$product_code = "EPAYTEST";
$transaction_uuid = uniqid('txn_');

// Store the POST data (appointment form) in session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['esewa_appointment_data'] = $_POST;
}

$secret_key = "8gBm/:&EnhH.1/q";
$message = "total_amount=$total_amount,transaction_uuid=$transaction_uuid,product_code=$product_code";
$hash = hash_hmac('sha256', $message, $secret_key, true);
$signature = base64_encode($hash);

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
$success_url = $base_url . "/esewa_return.php?status=Complete";
$failure_url = $base_url . "/esewa_return.php?status=failed";

?>
<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to eSewa...</title>
</head>
<body onload="document.forms['esewa_form'].submit()">
    <h3>Redirecting to eSewa Payment Gateway...</h3>
    <form name="esewa_form" action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
        <input type="hidden" name="amount" value="<?php echo $amount; ?>">
        <input type="hidden" name="tax_amount" value="<?php echo $tax_amount; ?>">
        <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
        <input type="hidden" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>">
        <input type="hidden" name="product_code" value="<?php echo $product_code; ?>">
        <input type="hidden" name="product_service_charge" value="0">
        <input type="hidden" name="product_delivery_charge" value="0">
        <input type="hidden" name="success_url" value="<?php echo $success_url; ?>">
        <input type="hidden" name="failure_url" value="<?php echo $failure_url; ?>">
        <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
        <input type="hidden" name="signature" value="<?php echo $signature; ?>">
    </form>
</body>
</html>