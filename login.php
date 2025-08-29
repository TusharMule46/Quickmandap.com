<?php
session_start();
include 'db.php'; // Make sure this file exists and connects properly

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR mobile_no = ?");
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_type'] = $user['role']; 

            if ($user['role'] === 'user') {
                echo 'LOGIN_SUCCESS_USER';
            } elseif ($user['role'] === 'vendor') {
                echo 'LOGIN_SUCCESS_VENDOR';
            }
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Mandap.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Your existing CSS styles remain the same */
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 20px;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1615184697985-c9bde1b07da7?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fGN1bHR1cmUlMjBvZiUyMGluZGlhfGVufDB8fDB8fHww') no-repeat center center/cover;
        }

        .auth-card {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 450px;
            padding: 40px;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-header h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .auth-form .form-group {
            margin-bottom: 20px;
        }

        .auth-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .auth-form input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .auth-form input:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .auth-form .btn {
            width: 100%;
            margin-top: 10px;
        }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
        }

        .auth-footer a {
            color: var(--primary-color);
            font-weight: 500;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            width: auto;
            margin-right: 8px;
        }

        .social-login {
            margin-top: 30px;
            text-align: center;
        }

        .social-login p {
            margin-bottom: 15px;
            position: relative;
        }

        .social-login p::before,
        .social-login p::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 35%;
            height: 1px;
            background-color: var(--border-color);
        }

        .social-login p::before {
            left: 0;
        }

        .social-login p::after {
            right: 0;
        }

        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--light-color);
            color: var(--dark-color);
            transition: var(--transition);
        }

        .social-button:hover {
            transform: translateY(-3px);
        }

        .social-button.facebook {
            background-color: #3b5998;
            color: var(--white);
        }

        .social-button.google {
            background-color: #db4437;
            color: var(--white);
        }

        .social-button.twitter {
            background-color: #1da1f2;
            color: var(--white);
        }

        .social-button i {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

<header>
    <div class="container">
        <div class="logo">
            <h1>Mandap<span>.com</span></h1>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="services.html">Services</a></li>
                <li><a href="vendors.html">Vendors</a></li>
                <li><a href="packages.html">Packages</a></li>
                <li><a href="contact.html">Contact</a></li>
            </ul>
            <div class="auth-buttons">
                <a href="services.html" class="btn btn-outline active">Login</a>
                <a href="signup.html" class="btn btn-primary">Sign Up</a>
            </div>
            <div class="hamburger">
                <span></span><span></span><span></span>
            </div>
        </nav>
    </div>
</header>

<section class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Welcome Back</h2>
            <p>Login to access your account</p>
        </div>
        <form class="auth-form" id="login-form">
            <div class="form-group">
                <label for="email">Email / Mobile</label>
                <input type="text" id="email" name="email" placeholder="Enter your email or mobile number" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="remember-forgot">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="#">Forgot Password?</a>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <div class="social-login">
            <p>Or login with</p>
            <div class="social-buttons">
                <a href="#" class="social-button facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-button google"><i class="fab fa-google"></i></a>
                <a href="#" class="social-button twitter"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
        <div class="auth-footer">
            <p>Don't have an account? <a href="signup.html">Sign Up</a></p>
        </div>
    </div>
</section>

<script>
// Function to get URL parameters
function getUrlParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

// Function to handle redirect after successful login
function handleLoginRedirect() {
    const packageType = getUrlParameter('package');
    const returnUrl = getUrlParameter('returnUrl');
    
    if (packageType) {
        // User came from package booking, redirect to booking page
        window.location.href = `book.php?package=${packageType}`;
        return true;
    } else if (returnUrl) {
        // General return URL
        window.location.href = decodeURIComponent(returnUrl);
        return true;
    }
    
    return false; // No redirect parameters found, use default behavior
}

// Show package info if coming from booking
document.addEventListener('DOMContentLoaded', function() {
    const packageType = getUrlParameter('package');
    
    if (packageType) {
        // Create or update a message div to show booking intent
        let messageDiv = document.getElementById('booking-message');
        if (!messageDiv) {
            messageDiv = document.createElement('div');
            messageDiv.id = 'booking-message';
            messageDiv.style.cssText = `
                background: #e3f2fd;
                color: #1976d2;
                padding: 15px;
                border-radius: 8px;
                margin-bottom: 20px;
                border: 1px solid #bbdefb;
                text-align: center;
            `;
            
            // Insert before the form
            const form = document.getElementById('login-form');
            if (form) {
                form.parentNode.insertBefore(messageDiv, form);
            }
        }
        
        messageDiv.innerHTML = `
            <strong>📦 Booking Intent Detected</strong><br>
            You're about to book the <strong>${packageType.charAt(0).toUpperCase() + packageType.slice(1)} Package</strong>.<br>
            Please login to continue with your booking.
        `;
    }
});

// Login form submission
document.getElementById('login-form').addEventListener('submit', function (e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    
    const formData = new FormData();
    formData.append("email", email);
    formData.append("password", password);
    
    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        console.log("Server response:", result);
        
        if (result === 'LOGIN_SUCCESS_USER') {
            // Show success popup message
            alert('You have successfully logged in!');
            
            // Check if we need to handle package redirect first
            if (!handleLoginRedirect()) {
                // No package redirect, go to services.html
                window.location.href = 'vendors.html';
            }
        } else if (result === 'LOGIN_SUCCESS_VENDOR') {
            // Show success popup message for vendor
            alert('You have successfully logged in!');
            window.location.href = 'vendor-dashboard.html';
        } else {
            // Show error message
            alert(result);
        }
    })
    .catch(error => {
        console.error('Error during fetch:', error);
        alert('Something went wrong. Try again.');
    });
});
</script>

</body>
</html>