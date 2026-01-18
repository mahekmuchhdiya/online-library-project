<?php 
session_start();
include('config/db.php'); 

// સિક્યોરિટી ચેક
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; 

// પાસવર્ડ બદલવાનું લોજિક
if(isset($_POST['change_password'])) {
    $new_password = mysqli_real_escape_string($conn, $_POST['password']);
    if(!empty($new_password)) {
        $query = "UPDATE users SET password='$new_password' WHERE id=$user_id";
        if(mysqli_query($conn, $query)) {
            echo "<script>alert('✅ પાસવર્ડ સફળતાપૂર્વક બદલાઈ ગયો!'); window.location.href='profile.php';</script>";
        }
    }
}

// ડેટા મેળવવો
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id"));
$count_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM issued_books WHERE user_id=$user_id"));

include('includes/header.php'); 
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.85)), url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=1920&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        margin: 0;
        display: flex;
        flex-direction: column; /* ફૂટરને નીચે રાખવા માટે */
        color: white;
    }

    .content-wrapper {
        flex: 1; /* આ ફૂટરને હંમેશા નીચે રાખશે */
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 50px 20px;
    }

    .mini-glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 25px;
        padding: 30px;
        width: 100%;
        max-width: 380px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        text-align: center;
    }

    .avatar-icon {
        width: 70px; height: 70px;
        background: #fbbf24; color: #1e293b;
        border-radius: 50%; display: flex;
        align-items: center; justify-content: center;
        margin: 0 auto 20px; font-size: 35px;
        box-shadow: 0 8px 15px rgba(251, 191, 36, 0.3);
    }

    .info-tile {
        background: rgba(0, 0, 0, 0.25); border-radius: 15px;
        padding: 12px 15px; margin-bottom: 12px;
        display: flex; align-items: center; text-align: left;
        border: 1px solid rgba(255,255,255,0.05);
    }

    .info-tile i { font-size: 1.2rem; color: #fbbf24; margin-right: 15px; }
    
    .tile-label { font-size: 0.65rem; color: #94a3b8; text-transform: uppercase; display: block; }
    .tile-value { font-size: 0.95rem; font-weight: 600; color: #fff; }

    .pass-update-area {
        margin-top: 20px; padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .small-input {
        width: 100%; padding: 12px; background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px;
        color: white; font-size: 0.9rem; margin-bottom: 12px; outline: none;
    }

    .btn-compact {
        width: 100%; padding: 12px; background: #fbbf24; color: #1e293b;
        border: none; border-radius: 12px; font-weight: 800; font-size: 0.9rem;
        cursor: pointer; transition: 0.3s;
    }

    .btn-compact:hover { background: #fff; transform: translateY(-2px); }

    /* --- WHITE FOOTER STYLE --- */
    .footer-white {
        background-color: #ffffff;
        color: #1e293b;
        padding: 20px 0;
        text-align: center;
        font-weight: 500;
        font-size: 0.9rem;
        border-top: 1px solid #e2e8f0;
        width: 100%;
    }

</style>

<div class="content-wrapper">
    <div class="mini-glass-card">
        <div class="avatar-icon">
            <i class="bi bi-person-circle"></i>
        </div>
        
        <h4 class="fw-bold mb-4">My Profile</h4>

        <div class="info-tile">
            <i class="bi bi-person"></i>
            <div>
                <span class="tile-label">Full Name</span>
                <span class="tile-value"><?php echo htmlspecialchars($user['fullname']); ?></span>
            </div>
        </div>

        <div class="info-tile">
            <i class="bi bi-envelope"></i>
            <div>
                <span class="tile-label">Email</span>
                <span class="tile-value"><?php echo htmlspecialchars($user['email']); ?></span>
            </div>
        </div>

        <div class="info-tile">
            <i class="bi bi-book"></i>
            <div>
                <span class="tile-label">Books Issued</span>
                <span class="tile-value"><?php echo $count_data['total']; ?> Books Total</span>
            </div>
        </div>

        <div class="pass-update-area">
            <form method="POST">
                <input type="password" name="password" class="small-input" placeholder="New Password" required>
                <button type="submit" name="change_password" class="btn-compact">
                    UPDATE PASSWORD 🔒
                </button>
            </form>
        </div>

        <a href="index.php" class="d-block mt-4 text-decoration-none text-muted small">
            <i class="bi bi-arrow-left"></i> Home
        </a>
    </div>
</div>

<footer class="footer-white">
    <div class="container">
        <span class="copyright-badge">© 2025 </span> 
        My Library Project | Developed by Muchhdiya Mahek
    </div>
</footer>