<?php 
session_start(); 
$conn = new mysqli("localhost", "root", "", "weddinginfo"); 
$port = 3306; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {     
    if (
        isset($_POST['fullname'], $_POST['email'], $_POST['mobile'], $_POST['password'], $_POST['confirm-password'], $_POST['role'])
    ) {         
        $name = $_POST['fullname'];         
        $email = $_POST['email'];         
        $phone = $_POST['mobile'];         
        $password = $_POST['password'];         
        $confirm_password = $_POST['confirm-password'];         
        $role = $_POST['role'];          
        
        if ($password !== $confirm_password) {             
            echo "<script>alert('Passwords do not match!'); window.history.back();</script>";             
            exit;         
        }          
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);         
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");         
        $stmt->bind_param("s", $email);         
        $stmt->execute();         
        $stmt->store_result();          
        
        if ($stmt->num_rows > 0) {             
            echo "<script>alert('Email already registered!'); window.history.back();</script>";         
        } else {             
            $stmt = $conn->prepare("INSERT INTO users (name, email, mobile_no, password, role) VALUES (?, ?, ?, ?, ?)");             
            $stmt->bind_param("sssss", $name, $email, $phone, $hashed_password, $role);             
            
            if ($stmt->execute()) {                 
                $_SESSION['user_id'] = $stmt->insert_id;                 
                $_SESSION['user_name'] = $name;                 
                $_SESSION['user_role'] = $role;                  
                
                // NEW REDIRECT HANDLING LOGIC
                // Check for package booking redirect first
                if (isset($_POST['package']) && isset($_POST['returnUrl'])) {
                    $package = $_POST['package'];
                    $returnUrl = $_POST['returnUrl'];
                    
                    // Redirect back to package page with booking intent
                    $redirectUrl = $returnUrl . "?package=" . urlencode($package) . "&loginSuccess=true";
                    header("Location: " . $redirectUrl);
                    exit();
                } 
                // Check for general return URL
                elseif (isset($_POST['returnUrl'])) {
                    // General return URL
                    header("Location: " . $_POST['returnUrl']);
                    exit();
                } 
                // Default role-based redirects
                else {
                    if ($role === 'user') {                     
                        header("Location:login.php");                 
                    } else {                     
                        header("Location: vendor-dashboard.html");                 
                    }
                }
                exit();             
            } else {                               
                echo "<script>alert('Database error: " . $conn->error . "'); window.history.back();</script>";             
            }         
        }     
    } else {         
        echo "<script>alert('Please fill in all fields.'); window.history.back();</script>";     
    } 
}

// Get URL parameters for pre-filling hidden form fields
$package = isset($_GET['package']) ? htmlspecialchars($_GET['package']) : '';
$returnUrl = isset($_GET['returnUrl']) ? htmlspecialchars($_GET['returnUrl']) : '';
?>