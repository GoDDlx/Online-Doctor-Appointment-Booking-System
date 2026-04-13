<!DOCTYPE html>
<html>
<head>
    <title>✅ Appointment Booked!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h1 class="display-4 text-success mb-3">Success!</h1>
                        <p class="lead mb-4">Your appointment has been booked successfully.</p>
                        
                        <div class="alert alert-info">
                            <strong>Method:</strong> <?= htmlspecialchars($_GET['method'] ?? 'Unknown') ?><br>
                            <strong>Transaction:</strong> <?= htmlspecialchars($_GET['txn'] ?? 'N/A') ?>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="index.php" class="btn btn-primary btn-lg">Book Another</a>
                            <a href="check-appointment.php" class="btn btn-outline-primary btn-lg">View Appointments</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>