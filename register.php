<?php 
session_start();
include('config/db.php'); 

if(isset($_POST['register'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // તમે સુરક્ષા માટે password_hash પણ વાપરી શકો

    // પહેલા ચેક કરો કે આ ઈમેઈલ પહેલેથી વપરાયેલો તો નથી ને?
    $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    
    if(mysqli_num_rows($check_email) > 0) {
        echo "<script>alert('❌ આ ઈમેઈલ પહેલેથી રજિસ્ટર્ડ છે!');</script>";
    } else {
        // નવો યુઝર ડેટાબેઝમાં ઉમેરો
        $sql = "INSERT INTO users (fullname, email, password) VALUES ('$fullname', '$email', '$password')";
        
        if(mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('✅ રજિસ્ટ્રેશન સફળ! હવે લોગિન કરો.');
                    window.location.href='login.php';
                  </script>";
        } else {
            echo "<script>alert('❌ કંઈક ભૂલ થઈ છે, ફરી પ્રયાસ કરો.');</script>";
        }
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
    .register-wrapper { flex: 1; display: flex; justify-content: center; align-items: center; padding: 20px; }
    .glass-card {
        background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 30px;
        width: 100%; max-width: 450px; padding: 40px; box-shadow: 0 25px 50px rgba(0,0,0,0.5); text-align: center;
    }
    .input-group { position: relative; margin-bottom: 20px; }
    .input-group i { position: absolute; left: 15px; top: 15px; color: #fbbf24; }
    .reg-input {
        width: 100%; padding: 12px 12px 12px 45px;
        background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255,255,255,0.2);
        border-radius: 12px; color: white; outline: none; box-sizing: border-box;
    }
    .btn-reg {
        width: 100%; padding: 14px; background: #fbbf24; border: none;
        border-radius: 12px; font-weight: 800; cursor: pointer; transition: 0.3s;
    }
    .btn-reg:hover { background: #fff; transform: translateY(-2px); }
</style>

<div class="register-wrapper">
    <div class="glass-card">
        <h2 style="color: #fbbf24; font-weight: 800; margin-bottom: 30px;">📝 CREATE ACCOUNT</h2>
        <form method="POST">
            <div class="input-group">
                <i class="bi bi-person-fill"></i>
                <input type="text" name="fullname" class="reg-input" placeholder="Full Name" required>
            </div>
            <div class="input-group">
                <i class="bi bi-envelope-fill"></i>
                <input type="email" name="email" class="reg-input" placeholder="Email Address" required>
            </div>
            <div class="input-group">
                <i class="bi bi-lock-fill"></i>
                <input type="password" name="password" class="reg-input" placeholder="Create Password" required>
            </div>
            <button type="submit" name="register" class="btn-reg">REGISTER NOW 🚀</button>
        </form>
        <p style="margin-top: 20px; color: #ccc;">Already have an account? <a href="login.php" style="color: #fbbf24; text-decoration: none;">Login Here</a></p>
    </div>
</div>