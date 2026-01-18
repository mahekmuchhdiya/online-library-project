<?php
session_start();
include('config/db.php');
include('includes/header.php');

// જો યુઝર લોગિન ન હોય તો
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('કૃપા કરીને પહેલા લોગિન કરો'); window.location='login.php';</script>";
    exit();
}

$u_id = $_SESSION['user_id'];
$today = date('Y-m-d');

// --- ૧. ISSUE REQUEST હેન્ડલ કરવાનો કોડ ---


// --- ૨. સબસ્ક્રિપ્શન સ્ટેટસ ચેક કરો ---
$has_plan = false;
$res = mysqli_query($conn, "SELECT is_premium, plan_expiry FROM users WHERE id = '$u_id'");
$u_data = mysqli_fetch_assoc($res);
if ($u_data && $u_data['is_premium'] == 1 && $u_data['plan_expiry'] >= $today) {
    $has_plan = true;
}
?>

<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* બેકગ્રાઉન્ડ સેટિંગ્સ */
        body { 
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), 
                        url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=1920');
            background-size: cover; 
            background-position: center; 
            background-attachment: fixed;
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: white; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container { flex: 1; }

        /* કેટેગરી બોક્સ - હવે સફેદ થીમમાં */
        .cat-box { 
            background: rgba(255, 255, 255, 0.1); 
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px; 
            padding: 40px 20px; 
            text-align: center; 
            transition: 0.4s; 
            cursor: pointer; 
            backdrop-filter: blur(10px);
        }
        
        .cat-box:hover { 
            transform: translateY(-10px); 
            background: #ffffff; /* પીળાને બદલે સફેદ હોવર */
            color: #1e293b; /* હોવર પર ટેક્સ્ટ ડાર્ક થશે */
            box-shadow: 0 15px 30px rgba(255, 255, 255, 0.2);
        }

        .cat-box i { font-size: 3rem; margin-bottom: 15px; display: block; }

        /* મોડલ ડિઝાઇન */
        .modal-content { border-radius: 20px; color: #333; overflow: hidden; border: none; }
        
        /* ઇશ્યૂ બટન - બ્લુ અને વ્હાઇટ થીમ */
        .btn-issue { 
            background: #4361ee; 
            color: white; 
            font-weight: 700; 
            border: none; 
            padding: 8px 18px;
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn-issue:hover { 
            background: #3046bc; 
            color: white; 
            transform: scale(1.05);
        }

        /* અનલોક બટન - રેડ થીમ (પ્રીમિયમ માટે) */
        .btn-unlock {
            background: #ef4444;
            color: white;
            font-weight: 700;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
        }

        /* સફેદ ફૂટર */
        .white-footer {
            background: #ffffff;
            color: #333;
            padding: 20px 0;
            text-align: center;
            font-weight: 500;
            border-top: 1px solid #eeeeee;
            width: 100%;
            margin-top: 60px;
        }
        .dev-name {
            color: #4361ee;
            font-weight: 800;
        }
    </style>
</head>
<body>

<div class="container py-5 text-center">
    <h1 class="fw-bold text-white mb-2" style="font-size: 3.2rem; text-shadow: 0 4px 15px rgba(0,0,0,0.5);">
        ✨ DIGITAL KNOWLEDGE
    </h1>
    <p class="mb-5 fs-5 opacity-75 text-white">All books Read</p>

    <div class="row g-4 justify-content-center">
        <?php
        $cat_query = mysqli_query($conn, "SELECT DISTINCT category FROM books");
        $i = 0;
        while($cat = mysqli_fetch_assoc($cat_query)) {
            $i++;
            $category_name = $cat['category'];
        ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="cat-box" data-bs-toggle="modal" data-bs-target="#modal<?php echo $i; ?>">
                    <i class="bi bi-book-half"></i>
                    <h5 class="mt-2 fw-bold text-uppercase"><?php echo $category_name; ?></h5>
                </div>
            </div>

            <div class="modal fade" id="modal<?php echo $i; ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title">BOOKS IN <?php echo strtoupper($category_name); ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-0 text-start">
                            <div class="list-group list-group-flush">
                                <?php
                                $safe_cat = mysqli_real_escape_string($conn, $category_name);
                                $books_res = mysqli_query($conn, "SELECT * FROM books WHERE category = '$safe_cat'");
                                
                                while($book = mysqli_fetch_assoc($books_res)) {
                                    $type = strtolower(trim($book['book_type'])); 
                                ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                                        <div>
                                            <b class="text-dark"><?php echo $book['title']; ?></b>
                                            <?php if($type == 'premium' || $type == 'primium'): ?>
                                                <span class="badge bg-primary ms-1">PREMIUM 🔒</span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div>
                                        <?php 
                                        if(($type == 'premium' || $type == 'primium') && !$has_plan) {
                                            echo '🔒 UNLOCK';
                                        } else {
                                            echo '<a href="books.php?action=issue&book_id='.$book['id'].'" class="btn btn-sm btn-issue">📖</a>';
                                        }
                                        ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<footer class="white-footer">
    <div class="container">
        <p class="mb-0">
            © 2025 My Library Project | Developed by Muchhdiya Mahek
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>