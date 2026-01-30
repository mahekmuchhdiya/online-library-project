<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('config/db.php'); 

if (!isset($_SESSION['user_id'])) {
    die("મહેરબાની કરીને લોગિન કરો.");
}

$u_id = $_SESSION['user_id'];
$success = false;
$error_msg = "";

$selected_plan = isset($_GET['plan']) ? mysqli_real_escape_string($conn, $_GET['plan']) : 'Monthly';

$plan_query = mysqli_query($conn, "SELECT price FROM plans WHERE plan_name = '$selected_plan'");
$plan_data = mysqli_fetch_assoc($plan_query);
$final_amount = ($plan_data) ? $plan_data['price'] : "0.00";

if (isset($_POST['submit_payment'])) {
    $txn_id = mysqli_real_escape_string($conn, $_POST['txn_id']);
    $p_name = $_POST['plan_name'];
    $p_amount = $_POST['amount'];

    // ૧. ૧૨ અંકનું કડક વેલિડેશન
    if (strlen($txn_id) != 12 || !is_numeric($txn_id)) {
        $error_msg = "ભૂલ: ટ્રાન્ઝેક્શન ID બરાબર ૧૨ અંકનો જ હોવો જોઈએ!";
    } else {
        // ૨. ડુપ્લીકેટ ચેક - આ નંબર બીજી વાર ના વપરાવો જોઈએ
        $check_duplicate = mysqli_query($conn, "SELECT id FROM payments WHERE transaction_id = '$txn_id'");
        
        if (mysqli_num_rows($check_duplicate) > 0) {
            $error_msg = "આ ટ્રાન્ઝેક્શન ID પહેલેથી ઉપયોગમાં લેવાઈ ગયો છે. કૃપા કરીને સાચો ID નાખો.";
        } else {
            // જો બધું સાચું હોય તો ડેટાબેઝ અપડેટ કરો
            $days = ($p_name == 'Yearly') ? '+365 days' : '+30 days';
            $expiry = date('Y-m-d', strtotime($days));

            $q = "INSERT INTO payments (user_id, amount, payment_status, transaction_id, plan_name, payment_date) 
                  VALUES ('$u_id', '$p_amount', 'Completed', '$txn_id', '$p_name', NOW())";
            
            if (mysqli_query($conn, $q)) {
                // સ્ટેટસ અપડેટ (તમારા ટેબલ મુજબ is_premium વાપર્યું છે)
                $update_user = "UPDATE users SET is_premium = 1, plan_expiry = '$expiry' WHERE id = '$u_id'";
                if(mysqli_query($conn, $update_user)){
                    $success = true;
                }
            } else {
                $error_msg = "ડેટાબેઝ એરર: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="gu">
<head>
    <title>Secure Payment - Library Pro</title>
    <style>
        body { background: #0f172a; color: white; font-family: sans-serif; text-align: center; padding: 20px; }
        .card { background: #1e293b; max-width: 420px; margin: 40px auto; padding: 30px; border-radius: 20px; border: 1px solid #facc15; }
        .gold { color: #facc15; font-weight: bold; }
        .qr-box { background: white; padding: 15px; border-radius: 15px; display: inline-block; margin: 15px 0; }
        .input-box { width: 100%; padding: 12px; margin: 10px 0; border-radius: 8px; border: 1px solid #475569; background: #334155; color: white; text-align: center; font-size: 16px; }
        .btn { background: #facc15; color: #0f172a; border: none; padding: 15px; width: 100%; border-radius: 10px; font-weight: bold; cursor: pointer; }
        .error-div { background: #ef4444; color: white; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>

<div class="card">
    <?php if ($success): ?>
        <h2 class="gold">Payment Successful! ✅</h2>
        <p>તમારો પ્લાન એક્ટિવેટ થઈ ગયો છે.</p>
        <a href="index.php" class="btn" style="display:block; text-decoration:none; margin-top:20px;">હોમ પેજ પર જાઓ</a>
    <?php else: ?>
        <h2 class="gold">Scan & Pay</h2>
        <p>Plan: <?php echo $selected_plan; ?> | Amount: ₹<?php echo $final_amount; ?></p>

        <div class="qr-box">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=upi://pay?pa=YOUR_UPI_ID@okicici&pn=LibraryPro&am=<?php echo $final_amount; ?>&cu=INR" alt="QR Code">
        </div>
        
        <?php if($error_msg): ?>
            <div class="error-div"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="plan_name" value="<?php echo $selected_plan; ?>">
            <input type="hidden" name="amount" value="<?php echo $final_amount; ?>">
            
            <input type="text" name="txn_id" class="input-box" 
                   placeholder="Enter 12 Digit UTR Number" 
                   maxlength="12" minlength="12" required 
                   oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            
            <button type="submit" name="submit_payment" class="btn">Confirm & Activate</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>