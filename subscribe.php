<?php
session_start();
include('config/db.php');
include('includes/header.php');

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
    body { 
        background-color: #0b0f19; 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        color: white;
        /* બેકગ્રાઉન્ડમાં હળવો ગ્લો આપવા માટે */
        background: radial-gradient(circle at 50% -20%, #1e293b, #0b0f19); 
    }

    .pricing-header { text-align: center; padding-top: 70px; margin-bottom: 60px; }
    
    /* ગોલ્ડ ટેક્સ્ટમાં એનિમેશન ગ્લો */
    .gold-text { 
        color: #facc15; 
        background: linear-gradient(to right, #facc15, #ffeb3b, #facc15);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 900;
    }
    
    .plan-card {
        background: #111827; /* એકદમ ડાર્ક બ્લેક-ગ્રે */
        border-radius: 28px;
        padding: 45px 35px;
        text-align: center;
        border: 1px solid rgba(255,255,255,0.05);
        transition: all 0.4s ease;
        position: relative;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    /* કાર્ડ પર હોવર ઇફેક્ટ */
    .plan-card:hover {
        transform: translateY(-15px);
        border-color: #facc15;
        box-shadow: 0 15px 40px rgba(250, 204, 21, 0.15);
    }

    /* Featured કાર્ડ માટે સ્પેશિયલ ઇફેક્ટ */
    .plan-card.featured {
        border: 1px solid rgba(250, 204, 21, 0.5);
        background: linear-gradient(145deg, #111827, #161b22);
    }

    .plan-card.featured::before {
        content: 'MOST POPULAR';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        background: #facc15;
        color: #000;
        padding: 4px 20px;
        font-size: 11px;
        font-weight: 800;
        border-radius: 0 0 12px 12px;
    }

    .price-amount {
        font-size: 55px;
        font-weight: 800;
        margin: 15px 0;
        color: #fff;
    }

    /* બટન સ્ટાઇલ - જે હાઈલાઈટ થશે */
    .btn-buy {
        background: #facc15;
        color: #000;
        border: none;
        padding: 16px;
        border-radius: 15px;
        font-weight: 800;
        width: 100%;
        display: block;
        text-decoration: none;
        margin-top: 30px;
        transition: 0.3s;
        box-shadow: 0 4px 15px rgba(250, 204, 21, 0.2);
    }

    .btn-buy:hover {
        background: #fff;
        transform: scale(1.02);
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.2);
    }

    .text-muted { color: #94a3b8 !important; }
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