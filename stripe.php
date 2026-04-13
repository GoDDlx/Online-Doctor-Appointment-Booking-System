<?php
session_start();

// ==============================================================================
// 🛑 IMPORTANT: PUT YOUR STRIPE SECRET KEY HERE!
// It must start with 'sk_test_...'
// You can find it in your Stripe Dashboard under Developers > API keys
// ==============================================================================
$stripe_secret_key = "sk_test_placeholder_key_here";

// Store POST data to session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['stripe_appointment_data'] = $_POST;
}

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);

$success_url = $base_url . "/stripe_return.php?session_id={CHECKOUT_SESSION_ID}";
$cancel_url = $base_url . "/index.php?payment=cancelled";

$checkout_data = [
    'payment_method_types' => ['card'],
    'line_items' => [
        [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Doctor Appointment Booking',
                ],
                'unit_amount' => 1000, // 1000 cents = $10
            ],
            'quantity' => 1,
        ]
    ],
    'mode' => 'payment',
    'success_url' => $success_url,
    'cancel_url' => $cancel_url,
];

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/checkout/sessions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($checkout_data));
curl_setopt($ch, CURLOPT_USERPWD, $stripe_secret_key . ":" . ""); // HTTP Basic Auth uses Secret key as username

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    die("cURL Error #:" . $err);
}

$result = json_decode($response, true);

if (isset($result['url'])) {
    // Redirect to Stripe Hosted Checkout
    header("Location: " . $result['url']);
    exit;
} else {
    // Show error if Stripe rejected the request
    die("<h1>Stripe configuration error</h1><p>Did you replace the placeholder with your actual Secret Key (sk_test_...) instead of your Publishable key?</p><p>Response from Stripe: " . htmlspecialchars(json_encode($result)) . "</p>");
}
?>
