<?php
session_start();
include('config/db.php');
include('includes/header.php');

// જો યુઝર લોગિન ન હોય તો
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

// ૧. ડેટાબેઝમાંથી લેટેસ્ટ કિંમતો મેળવો
$monthly_query = mysqli_query($conn, "SELECT price FROM plans WHERE plan_name='Monthly'");
$monthly_data = mysqli_fetch_assoc($monthly_query);
$m_price = $monthly_data['price'];

$yearly_query = mysqli_query($conn, "SELECT price FROM plans WHERE plan_name='Yearly'");
$yearly_data = mysqli_fetch_assoc($yearly_query);
$y_price = $yearly_data['price'];
?>

<style>
    /* તમારી ઓરિજિનલ ડિઝાઇનના સ્ટીલિંગ */
    body { background-color: #0b0f19; font-family: 'Plus Jakarta Sans', sans-serif; color: white; }
    .pricing-header { text-align: center; padding-top: 50px; margin-bottom: 40px; }
    .gold-text { color: #facc15; }
    
    .plan-card {
        background: #161b22;
        border-radius: 20px;
        padding: 40px;
        text-align: center;
        border: 1px solid rgba(255,255,255,0.1);
        transition: 0.3s;
    }

    .plan-card.featured {
        border: 1.5px solid #facc15;
    }

    .price-amount {
        font-size: 45px;
        font-weight: 700;
        margin: 10px 0;
    }

    .btn-buy {
        background: #facc15;
        color: #000;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 800;
        width: 100%;
        display: block;
        text-decoration: none;
        margin-top: 20px;
    }

    .btn-buy:hover { background: #eab308; }
</style>

<div class="container">
    <div class="pricing-header">
        <h1 class="fw-bold">Choose Your <span class="gold-text">Premium Plan</span> 💎</h1>
        <p class="text-muted">કોઈપણ એક પ્લાન પસંદ કરો અને બધી બુક્સ અનલોક કરો.</p>
    </div>

    <div class="row justify-content-center g-4">
        <div class="col-md-4">
            <div class="plan-card">
                <h3 class="fw-bold">Monthly</h3>
                <div class="price-amount gold-text">₹<?php echo number_format($m_price, 0); ?></div>
                <p class="text-muted small">Valid for 30 Days</p>
                <a href="payment.php?plan=Monthly" class="btn-buy">Buy Now</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="plan-card featured">
                <div class="badge bg-warning text-dark mb-2" style="font-size: 10px;">BEST SELLER</div>
                <h3 class="fw-bold">Yearly</h3>
                <div class="price-amount gold-text">₹<?php echo number_format($y_price, 0); ?></div>
                <p class="text-muted small">Valid for 365 Days</p>
                <a href="payment.php?plan=Yearly" class="btn-buy">Buy Now</a>
            </div>
        </div>
    </div>
</div>