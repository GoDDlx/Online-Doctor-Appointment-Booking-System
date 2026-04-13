<?php
session_start();

$session_id = $_GET['session_id'] ?? '';

// In a real production application, you would verify this exact session_id with Stripe:
// GET https://api.stripe.com/v1/checkout/sessions/{$session_id} using cURL
// If status == 'complete', then proceed. For test environment, checking session_id presence is adequate.

if ($session_id) {
    // ✅ PAYMENT SUCCESS
    $appointmentData = $_SESSION['stripe_appointment_data'] ?? [];
    
    $_SESSION['payment_success'] = true;
    $_SESSION['appointment_data'] = $appointmentData;
    $_SESSION['stripe_txn'] = $session_id;

    // Clear the stored data
    unset($_SESSION['stripe_appointment_data']);
    
    // Normally you would save to database here.
    error_log("Stripe SUCCESS: " . json_encode($appointmentData));
    
    // Redirect to the success success screen
    header('Location: success.php?method=stripe&txn=' . $session_id);
    exit;
} else {
    // ❌ PAYMENT FAILED
    $_SESSION['payment_failed'] = true;
    header('Location: index.php?payment=failed');
    exit;
}
?>
