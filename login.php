<?php 
session_start();
include('config/db.php'); 

if(isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // ૧. ડેટાબેઝમાંથી યુઝર શોધો (અહીં પાસવર્ડ અને ઈમેલ ચેક થાય છે)
    $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    
    if(mysqli_num_rows($res) > 0) {
        $user = mysqli_fetch_assoc($res);
        
        // ૨. અહીં બ્લોક સ્ટેટસ ચેક કરો
        // જો status 0 હોય તો યુઝર બ્લોક છે
        if($user['status'] == 0) {
            echo "<script>alert('❌ તમારું એકાઉન્ટ બ્લોક છે! મહેરબાની કરીને એડમિનનો સંપર્ક કરો.'); window.location='login.php';</script>";
            exit();
        }

        // ૩. જો યુઝર બ્લોક નથી, તો જ સેશન બનાવો
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname']; 
        
        header("Location: index.php"); 
        exit();
    } else {
        echo "<script>alert('❌ ખોટો ઈમેઈલ અથવા પાસવર્ડ!');</script>";
    }
}
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    body {
        background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=1920&auto=format&fit=crop');
        background-size: cover; font-family: 'Plus Jakarta Sans', sans-serif;
        margin: 0; min-height: 100vh; display: flex; flex-direction: column;
    }
    .login-wrapper { flex: 1; display: flex; justify-content: center; align-items: center; }
    .glass-login-card {
        background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 30px;
        width: 100%; max-width: 400px; padding: 40px; box-shadow: 0 25px 50px rgba(0,0,0,0.5); text-align: center;
    }
    .login-input {
        width: 100%; padding: 12px 12px 12px 45px; margin-bottom: 20px;
        background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255,255,255,0.2);
        border-radius: 12px; color: white; outline: none;
    }
    .btn-login {
        width: 100%; padding: 14px; background: #fbbf24; border: none;
        border-radius: 12px; font-weight: 800; cursor: pointer; transition: 0.3s;
    }
    .btn-login:hover { background: #f59e0b; transform: translateY(-2px); }
</style>

<div class="login-wrapper">
    <div class="glass-login-card">
        <div style="font-size: 50px; color: #fbbf24; margin-bottom: 20px;"><i class="bi bi-person-circle"></i></div>
        <h2 style="color: white; margin-bottom: 30px; font-weight: 800;">Library Pro</h2>
        <form method="POST">
            <div style="position: relative;">
                <i class="bi bi-envelope-fill" style="position: absolute; left: 15px; top: 15px; color: #fbbf24;"></i>
                <input type="email" name="email" class="login-input" placeholder="Email Address" required>
            </div>
            <div style="position: relative;">
                <i class="bi bi-lock-fill" style="position: absolute; left: 15px; top: 15px; color: #fbbf24;"></i>
                <input type="password" name="password" class="login-input" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="btn-login">LOGIN NOW 🚀</button>
            <p style="margin-top: 20px; color: #ccc;">New user? <a href="register.php" style="color: #fbbf24; text-decoration: none; font-weight: bold;">Register Now</a></p>
        </form>
    </div>
</div>