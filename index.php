
<?php 
session_start();
include('config/db.php'); 
include('includes/header.php'); 

// પ્લાન ચેક કરવાનું લોજિક
$has_plan = false;
if(isset($_SESSION['user_id'])) {
    $u_id = $_SESSION['user_id'];
    $today = date('Y-m-d');
    $res = mysqli_query($conn, "SELECT is_premium, plan_expiry FROM users WHERE id = '$u_id'");
    $user = mysqli_fetch_assoc($res);
    if($user && $user['is_premium'] == 1 && $user['plan_expiry'] >= $today) {
        $has_plan = true;
    }
}
?>

<div class="p-5 mb-4 text-white rounded-3 shadow" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'); background-size: cover; background-position: center;">
    <div class="container-fluid py-5 text-center">
        <h1 class="display-4 fw-bold text-uppercase">Welcome to Library Pro</h1>
        <p class="fs-4 mt-3">જ્ઞાનનો અખૂટ ખજાનો હવે તમારી આંગળીના ટેરવે. પુસ્તકો શોધો અને ઓનલાઇન વાંચો.</p>
        
        <div class="mt-4">
            <a href="books.php" class="btn btn-warning btn-lg px-4 me-md-2 fw-bold shadow">
                Explore Books (પુસ્તકો જુઓ)
            </a>

            <?php if(!isset($_SESSION['user_id'])) { ?>
                <a href="register.php" class="btn btn-outline-light btn-lg px-4">Join Now</a>
            <?php } else if(!$has_plan) { ?>
                <a href="subscribe.php" class="btn btn-danger btn-lg px-4 fw-bold shadow animate-pulse">
                    ✨ Get Premium (પ્લાન લો)
                </a>
            <?php } else { ?>
                <button class="btn btn-success btn-lg px-4 fw-bold shadow animate-pulse" disabled>
                    <i class="bi bi-patch-check"></i> Premium Active
                </button>
            <?php } ?>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm p-3 border-top border-5 border-primary">
                <div class="card-body">
                    <h1 class="display-3 text-primary"><i class="bi bi-search"></i></h1>
                    <h4 class="fw-bold">Easy Search</h4>
                    <p class="text-muted">તમારા મનગમતા પુસ્તકો લેખક કે ટાઈટલ દ્વારા સેકન્ડોમાં શોધો.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm p-3 border-top border-5 border-success">
                <div class="card-body">
                    <h1 class="display-3 text-success"><i class="bi bi-star-fill"></i></h1>
                    <h4 class="fw-bold">Premium Access</h4>
                    <p class="text-muted">સબસ્ક્રિપ્શન સાથે બધી જ પ્રીમિયમ બુક્સ કોઈપણ રોકટોક વગર વાંચો.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm p-3 border-top border-5 border-warning">
                <div class="card-body">
                    <h1 class="display-3 text-warning"><i class="bi bi-phone-vibrate"></i></h1>
                    <h4 class="fw-bold">Read Anywhere</h4>
                    <p class="text-muted">મોબાઇલ કે કોમ્પ્યુટર પર ગમે ત્યારે ગમે ત્યાંથી તમારી લાઇબ્રેરી એક્સેસ કરો.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5 border-top border-bottom">
    <div class="container text-center">
        <?php if(!$has_plan) { ?>
            <h2 class="fw-bold text-dark">શું તમે બધી જ પ્રીમિયમ બુક્સ અનલોક કરવા માંગો છો?</h2>
            <p class="lead">માત્ર ₹499 માં આખા વર્ષ માટે અમર્યાદિત પુસ્તકો વાંચો.</p>
            <a href="subscribe.php" class="btn btn-primary btn-lg px-5 shadow">Upgrade Now</a>
        <?php } else { ?>
            <h2 class="fw-bold text-success">તમે અમારા પ્રીમિયમ મેમ્બર છો!</h2>
            <p class="lead">નવા પુસ્તકો રોજ ઉમેરવામાં આવે છે. વાંચવાનું ચાલુ રાખો.</p>
            <a href="books.php" class="btn btn-success btn-lg px-5 shadow">Start Reading</a>
        <?php } ?>
    </div>
</div>

<style>
/* બટન ધબકતું (Animate) કરવા માટે */
.animate-pulse {
    animation: pulse-animation 2s infinite;
}
@keyframes pulse-animation {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>

<?php include('includes/footer.php'); ?>
