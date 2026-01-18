<?php
// ૧. સેશન અને એરર રિપોર્ટિંગ ચાલુ કરો (જેથી બ્લેન્ક સ્ક્રીન ના આવે)
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// ૨. ડેટાબેઝ કનેક્શન - પાથ બરાબર ચેક કરજો
include('config/db.php'); 

// ૩. જો લોગિન ન હોય તો રીડાયરેક્ટ
if (!isset($_SESSION['user_id'])) {
    die("તમે લોગિન કરેલ નથી. મહેરબાની કરીને લોગિન કરો.");
}

$u_id = $_SESSION['user_id'];
$success = false;
$error_msg = "";

// ૪. URL માંથી પ્લાન લેવો
$selected_plan = isset($_GET['plan']) ? mysqli_real_escape_string($conn, $_GET['plan']) : 'Monthly';

// ૫. ડેટાબેઝમાંથી કિંમત લેવી
$plan_query = mysqli_query($conn, "SELECT price FROM plans WHERE plan_name = '$selected_plan'");
$plan_data = mysqli_fetch_assoc($plan_query);
$final_amount = ($plan_data) ? $plan_data['price'] : "0.00";

// ૬. પેમેન્ટ સબમિટ થાય ત્યારે
if (isset($_POST['submit_payment'])) {
    $txn_id = mysqli_real_escape_string($conn, $_POST['txn_id']);
    $p_name = $_POST['plan_name'];
    $p_amount = $_POST['amount'];
    $date = date('Y-m-d H:i:s');

    // યુઝર છે કે નહીં તે ચેક કરો (Foreign Key Safety)
    $check_user = mysqli_query($conn, "SELECT id FROM users WHERE id = '$u_id'");
    
    if (mysqli_num_rows($check_user) > 0) {
        $q = "INSERT INTO payments (user_id, amount, payment_status, transaction_id, plan_name, payment_date) 
              VALUES ('$u_id', '$p_amount', 'Completed', '$txn_id', '$p_name', '$date')";
        
        if (mysqli_query($conn, $q)) {
            $success = true;
        } else {
            $error_msg = "Database Error: " . mysqli_error($conn);
        }
    } else {
        $error_msg = "તમારું યુઝર આઈડી ($u_id) ડેટાબેઝમાં મળતું નથી.";
    }
}
?>

<!DOCTYPE html>
<html lang="gu">
<head>
    <title>Payment - Library Pro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { background: #0f172a; color: white; font-family: sans-serif; text-align: center; padding: 20px; }
        .card { background: #1e293b; max-width: 400px; margin: auto; padding: 30px; border-radius: 20px; border: 1px solid #facc15; }
        .gold { color: #facc15; }
        .input-box { width: 100%; padding: 12px; margin: 15px 0; border-radius: 8px; border: 1px solid #475569; background: #334155; color: white; box-sizing: border-box; }
        .btn { background: #facc15; color: #0f172a; border: none; padding: 15px; width: 100%; border-radius: 10px; font-weight: bold; cursor: pointer; }
        .qr-img { background: white; padding: 10px; border-radius: 10px; margin: 15px 0; width: 180px; }
    </style>
</head>
<body>

<div class="card">
    <?php if ($success): ?>
        <h2 class="gold">Payment Done! ✅</h2>
        <p>તમારો પ્લાન સફળતાપૂર્વક એક્ટિવેટ થયો છે.</p>
        <a href="books.php" style="color:#facc15; text-decoration:none;">વાંચવા માટે અહીં ક્લિક કરો</a>
    <?php else: ?>
        <h2 class="gold">Payment Detail</h2>
        <p>Plan: <b><?php echo $selected_plan; ?></b> | Amount: <b>₹<?php echo $final_amount; ?></b></p>
        
        <?php if($error_msg) echo "<p style='color:red;'>$error_msg</p>"; ?>

        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=upi://pay?pa=YOUR_ID@okicici&am=<?php echo $final_amount; ?>" class="qr-img">

        <form method="POST">
            <input type="hidden" name="plan_name" value="<?php echo $selected_plan; ?>">
            <input type="hidden" name="amount" value="<?php echo $final_amount; ?>">
            
            <input type="text" name="txn_id" class="input-box" placeholder="Enter Transaction ID (12 Digit)" required>
            <button type="submit" name="submit_payment" class="btn">Activate Now</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>