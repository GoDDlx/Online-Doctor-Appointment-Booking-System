<?php
session_start();

$status = $_REQUEST['status'] ?? '';

// eSewa v2 success sends base64 encoded data in 'data' parameter
if (isset($_GET['data'])) {
    $data_base64 = $_GET['data'];
    $esewa_data = json_decode(base64_decode($data_base64), true);
    
    if ($esewa_data && isset($esewa_data['status']) && $esewa_data['status'] === 'COMPLETE') {
        $status = 'Complete';
        $pid = $esewa_data['transaction_uuid'] ?? '';
    }
} else {
    // Fallback if transaction UUID is given directly or status is passed via our own URL appended parameter
    $pid = $_REQUEST['pid'] ?? uniqid();
}

$appointmentData = $_SESSION['esewa_appointment_data'] ?? [];

if ($status === 'Complete') {
    // ✅ PAYMENT SUCCESS
    $_SESSION['payment_success'] = true;
    $_SESSION['appointment_data'] = $appointmentData;
    $_SESSION['esewa_txn'] = $pid;
    
    // Clear the stored POST data
    unset($_SESSION['esewa_appointment_data']);
    
    // Optionally: save to database here...
    error_log("eSewa SUCCESS: " . json_encode($appointmentData));
    
    header('Location: success.php?method=esewa&txn=' . $_SESSION['esewa_txn']);
    exit;
} else {
    // ❌ PAYMENT FAILED
    $_SESSION['payment_failed'] = true;
    header('Location: index.php?payment=failed');
    exit;
}
?>