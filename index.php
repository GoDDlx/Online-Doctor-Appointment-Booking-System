<!doctype html>
<html lang="en">
<head>
    <title>Doctor Appointment Management System || Home Page</title>

    <!-- CSS FILES -->        
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/owl.carousel.min.css" rel="stylesheet">
    <link href="css/owl.theme.default.min.css" rel="stylesheet">
    <link href="css/templatemo-medic-care.css" rel="stylesheet">
    
    <!-- jQuery FIRST (required for AJAX) -->
    <script src="js/jquery.min.js"></script>
    
    <!-- Khalti Payment -->
    <script src="https://khalti.com/static/khalti-checkout.js"></script>
    
    <script>
        // Doctor fetch function
        function getdoctors(val) {
            if (val === '') {
                $("#doctorlist").html('<option value="">Select Doctor</option>');
                return;
            }
            
            $.ajax({
                type: "POST",
                url: "get_doctors.php",
                data: 'sp_id=' + val,
                beforeSend: function() {
                    $("#doctorlist").html('<option>Loading doctors...</option>');
                },
                success: function(data) {
                    $("#doctorlist").html(data);
                },
                error: function() {
                    $("#doctorlist").html('<option>Error loading doctors</option>');
                }
            });
        }

        // Form validation
        function validateForm() {
            const form = document.forms[0];
            const requiredFields = ['name', 'email', 'phone', 'date', 'time', 'specialization', 'doctorlist', 'payment_method'];
            
            for (let field of requiredFields) {
                const input = form[field];
                if (!input.value.trim()) {
                    alert(`Please fill ${field.replace('_', ' ')}`);
                    input.focus();
                    return false;
                }
            }
            
            // Email validation
            const email = form.email.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Please enter a valid email');
                return false;
            }
            
            // Phone validation (10 digits)
            const phone = form.phone.value.replace(/\D/g, '');
            if (phone.length !== 10) {
                alert('Please enter a valid 10-digit phone number');
                return false;
            }
            
            return true;
        }

        // Khalti configuration
        var config = {
            "publicKey": "test_public_key_xxxxx", // Replace with your actual key
            "productIdentity": "123456",
            "productName": "Doctor Appointment",
            "productUrl": "http://localhost/dams",
            "paymentPreference": ["KHALTI"],
            "eventHandler": {
                onSuccess: function(payload) {
                    // Store appointment data
                    const formData = {
                        name: document.getElementById('name').value,
                        email: document.getElementById('email').value,
                        phone: document.getElementById('phone').value,
                        date: document.getElementById('date').value,
                        time: document.getElementById('time').value,
                        specialization: document.getElementById('specialization').value,
                        doctor: document.getElementById('doctorlist').value,
                        message: document.getElementById('message').value,
                        khalti_token: payload.token,
                        amount: payload.amount
                    };
                    
                    localStorage.setItem('appointmentData', JSON.stringify(formData));
                    
                    fetch("khalti_verify.php", {
                        method: "POST",
                        headers: {"Content-Type": "application/json"},
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.text())
                    .then(data => {
                        alert("Payment Successful! Redirecting...");
                        window.location.href = "success.php";
                    })
                    .catch(err => {
                        alert("Payment verification failed. Please contact support.");
                        console.error(err);
                    });
                },
                onError: function(error) {
                    console.log(error);
                    alert("Payment failed. Please try again.");
                }
            }
        };

        var checkout = new KhaltiCheckout(config);

        function payWithKhalti() {
            checkout.show({amount: 1000}); // Rs 10 - make dynamic if needed
        }

        // Payment handler
        function handlePayment() {
            if (!validateForm()) return;
            
            const method = document.querySelector("[name='payment_method']").value;
            
            if (method === "khalti") {
                payWithKhalti();
            } else if (method === "esewa") {
                document.forms[0].action = "esewa.php";
                document.forms[0].submit();
            } else if (method === "stripe") {
                document.forms[0].action = "stripe.php";
                document.forms[0].submit();
            } else {
                alert("Please select a payment method");
            }
        }

        // Set minimum date to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date').setAttribute('min', today);
        });
    </script>
</head>

<body id="top">
    <main>
        <nav class="navbar navbar-expand-lg bg-light fixed-top shadow-lg">
            <div class="container">
                <a class="navbar-brand mx-auto d-lg-none" href="index.php">
                    Doctor Appointment
                    <strong class="d-block">Management System</strong>
                </a>
                <a class="navbar-brand d-none d-lg-block" href="index.php">
                    Doctor Appointment
                    <strong class="d-block">Management System</strong>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="check-appointment.php">Check Appointment</a></li>
                        <li class="nav-item"><a class="nav-link" href="#booking">Booking</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                        <li class="nav-item active"><a class="nav-link" href="doctor/login.php">Doctor Login</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero" id="hero">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div id="myCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="images/slider/doctor-with-crossed-arms.jpg" class="img-fluid" alt="Doctor">
                                </div>
                                <div class="carousel-item">
                                    <img src="images/slider/young-asian-female-dentist-white-coat-posing-clinic-equipment.jpg" class="img-fluid" alt="Dentist">
                                </div>
                                <div class="carousel-item">
                                    <img src="images/slider/stethoscope-closeup.jpg" class="img-fluid" alt="Stethoscope">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="section-padding" id="about">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-12">
                        <h2 class="mb-lg-3 mb-3">About Us</h2>
                        <div>
                            <h1 style="color:blue;">
                                <i class="fa fa-gg" style="font-size:30px;color: blue;"></i> DAMS
                            </h1>
                            <p><b><a href="index.php" style="color:blue;">Doctor Appointment System</a></b> where everything seems pointless, whether it's job, education or business, health is everyone's topmost priority. 
                            Because if we're not healthy, how could we focus on other things in life? Doctors are the ones that come first in the line.
                            <br><br>
                            This project is a smart appointment booking system that provides patients an easy way of booking a doctor's appointment online. 
                            This web-based application overcomes the issue of managing and booking appointments according to user's choice or demands.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-5 col-12 mx-auto">
                        <div class="featured-circle bg-white shadow-lg d-flex justify-content-center align-items-center">
                            <p class="featured-text">
                                <span class="featured-number">10</span> Years<br>of Experiences
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Gallery -->
        <section class="gallery">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-6 ps-0">
                        <img src="images/gallery/medical-clinic.jpg" class="img-fluid galleryImage" alt="Medical clinic" title="Medical clinic">
                    </div>
                    <div class="col-lg-6 col-6 pe-0">
                        <img src="images/gallery/female-doctor-with-presenting-hand-gesture.jpg" class="img-fluid galleryImage" alt="Female doctor" title="Female doctor">
                    </div>
                </div>
            </div>
        </section>

        <!-- Booking Form -->
        <section class="section-padding" id="booking">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-12 mx-auto">
                        <div class="booking-form">
                            <h2 class="text-center mb-lg-3 mb-2">Book an Appointment</h2>
                            
                            <form role="form" method="post" id="appointmentForm">
                                <div class="row g-3">
                                    <div class="col-lg-6 col-12">
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Full name" required>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Email address" required>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <input type="tel" name="phone" id="phone" class="form-control" placeholder="Enter Phone Number" maxlength="10" required>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <input type="date" name="date" id="date" class="form-control" required>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <input type="time" name="time" id="time" class="form-control" required>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <select name="specialization" id="specialization" class="form-control" required onchange="getdoctors(this.value)">
                                            <option value="">Select specialization</option>
                                            <option value="1">Cardiologist</option>
                                            <option value="2">Dermatologist</option>
                                            <option value="3">Neurologist</option>
                                            <option value="5">Paediatrician</option>
                                            <option value="6">Psychiatrist</option>
                                            <option value="7">Radiologist</option>
                                            <option value="8">Anesthesiologist</option>
                                            <option value="9">Endocrinologist</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <select name="doctorlist" id="doctorlist" class="form-control" required>
                                            <option value="">Select Doctor</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <select name="payment_method" id="payment_method" class="form-control" required>
                                            <option value="">Select Payment Method</option>
                                            <option value="khalti">Khalti</option>
                                            <option value="esewa">eSewa</option>
                                            <option value="stripe">Stripe</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <textarea class="form-control" rows="5" id="message" name="message" placeholder="Additional Message"></textarea>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="button" class="btn btn-primary btn-lg px-5" onclick="handlePayment()">
                                            Proceed to Pay <i class="bi bi-credit-card ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="site-footer section-padding" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 me-auto col-12">
                    <h5 class="mb-lg-4 mb-3">Timing</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex">10:30 am to 7:30 pm</li>
                    </ul>
                    <h5 class="mb-lg-4 mb-3 mt-4">Email</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex">DAMS@gmail.com</li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 col-12 my-4 my-lg-0">
                    <h5 class="mb-lg-4 mb-3">Our Clinic</h5>
                    <p>No, 23, 80 Feet Main Rd,<br>
                    Kuleshwor Mahadev, Ward No. 15,<br>
                    Kathmandu Metropolitan City,<br>
                    Kathmandu, Bagmati Province 44600, Nepal</p>
                </div>
                <div class="col-lg-3 col-md-6 col-12 ms-auto">
                    <h5 class="mb-lg-4 mb-2">Socials</h5>
                    <ul class="social-icon">
                        <li><a href="#" class="social-icon-link bi-facebook"></a></li>
                        <li><a href="#" class="social-icon-link bi-twitter"></a></li>
                        <li><a href="#" class="social-icon-link bi-instagram"></a></li>
                        <li><a href="#" class="social-icon-link bi-youtube"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- JAVASCRIPT FILES -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/scrollspy.min.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>