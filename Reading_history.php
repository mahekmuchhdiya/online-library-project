<?php
session_start();
include('config/db.php');
include('includes/header.php');

// જો યુઝર લોગિન ન હોય તો
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

$u_id = $_SESSION['user_id'];
?>

<style>
    /* પ્રીમિયમ ડાર્ક થીમ બેકગ્રાઉન્ડ */
    body {
        background: linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.85)), url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=1920'); 
        background-size: cover;
        background-attachment: fixed;
        color: white;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .library-header {
        padding: 60px 0 40px;
        text-align: center;
    }

    /* હેડિંગ - હવે પીળાને બદલે સફેદ */
    .library-header h1 {
        font-weight: 800;
        color: #ffffff; 
        text-shadow: 0 4px 10px rgba(0,0,0,0.5);
    }

    /* ગ્લાસ મોર્ફિઝમ બુક કાર્ડ્સ */
    .book-card {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(15px);
        padding: 30px;
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.1);
        transition: 0.4s ease;
        height: 100%;
        text-align: center;
    }

    .book-card:hover {
        transform: translateY(-10px);
        background: rgba(255, 255, 255, 0.15);
        border-color: #ffffff; /* હોવર બોર્ડર હવે સફેદ */
    }

    .book-title {
        color: #ffffff;
        font-weight: 700;
        margin: 15px 0;
        min-height: 50px;
    }

    .category-badge {
        background: #4361ee;
        color: white;
        padding: 5px 15px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* READ બટન - હવે બ્લુ અને સફેદ થીમમાં */
    .btn-read {
        display: block;
        background: #ffffff; 
        color: #1e293b;
        padding: 12px;
        text-decoration: none !important;
        border-radius: 12px;
        font-weight: 800;
        transition: 0.3s;
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
    }

    .btn-read:hover {
        background: #4361ee;
        color: white;
        transform: scale(1.05);
    }

    /* પેન્ડિંગ સ્ટેટસ - હવે સફેદ બોર્ડર સાથે */
    .status-pending {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 12px;
        border-radius: 12px;
        font-weight: bold;
        border: 1px dashed #ffffff;
        opacity: 0.8;
    }

    /* શુદ્ધ સફેદ ફૂટર */
    .white-footer {
        background: #ffffff;
        color: #333;
        padding: 20px 0;
        text-align: center;
        margin-top: 220px;
        font-weight: 500;
        border-top: 1px solid #eeeeee;
    }

    .dev-name {
        color: #4361ee;
        font-weight: 800;
    }
</style>

<div class="library-header">
    <div class="container">
        <h1>✨ DIGITAL KNOWLEDGE</h1>
        <p class="lead opacity-75">તમારી પર્સનલ લાઈબ્રેરી: અહીં તમે ઇશ્યૂ કરેલા પુસ્તકો છે.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <?php
        $query = "SELECT ib.status, b.title, b.pdf_file, ib.txn_id, b.category 
                  FROM issued_books ib 
                  JOIN books b ON ib.book_id = b.id 
                  WHERE ib.user_id = '$u_id' ORDER BY ib.id DESC";
        
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="col-md-4 mb-4">
                <div class="book-card">
                    <span class="category-badge"><?php echo $row['category']; ?></span>
                    <h4 class="book-title"><?php echo $row['title']; ?></h4>
                    
                    <?php if($row['status'] == 'issued' || $row['status'] == 'approved') { ?>
                        <a href="uploads/<?php echo $row['pdf_file']; ?>" target="_blank" class="btn-read">
                            📖 READ PDF NOW
                        </a>
                        <?php if(!empty($row['txn_id'])) { ?>
                            <div class="mt-3 text-info" style="font-size: 0.85rem;">
                                <i class="bi bi-shield-check"></i> UTR: <?php echo $row['txn_id']; ?>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="status-pending">
                             ⏳ Verification Pending...
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php 
            } 
        } else {
            echo '<div class="col-12 text-center py-5">
                    <h3 class="text-white">તમારી લાઈબ્રેરી ખાલી છે.</h3>
                    <a href="books.php" class="btn btn-outline-light mt-3 fw-bold">પુસ્તક મેળવો</a>
                  </div>';
        }
        ?>
    </div>
</div>

<footer class="white-footer">
    <div class="container">
        <p class="mb-0">
            © 2025 My Library Project | Developed by Muchhdiya Mahek
        </p>
    </div>
</footer>