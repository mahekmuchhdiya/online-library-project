<?php 
session_start();
include('config/db.php'); 
include('includes/header.php'); 

if (!isset($_SESSION['user_id'])) { 
    echo "<script>alert('મહેરબાની કરીને લોગિન કરો!'); window.location='login.php';</script>";
    exit(); 
}

$u_id = $_SESSION['user_id'];
$u_name = $_SESSION['fullname'];

// ૧. યુઝર ડેટા અને લિમિટ ચેક
$user_res = mysqli_query($conn, "SELECT is_premium FROM users WHERE id = '$u_id'");
$user_data = mysqli_fetch_assoc($user_res);
$is_premium_user = (int)$user_data['is_premium']; 

$limit_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM issued_books WHERE user_id = '$u_id' AND status = 'issued'");
$limit_data = mysqli_fetch_assoc($limit_res);
$current_issued_count = (int)$limit_data['total'];
$max_limit = 10; 

// ૨. બુક ઇશ્યૂ પ્રોસેસ
if(isset($_POST['issue'])) {
    $book_id = mysqli_real_escape_string($conn, $_POST['book_id']);
    $b_query = mysqli_query($conn, "SELECT book_type FROM books WHERE id = '$book_id'");
    $b_data = mysqli_fetch_assoc($b_query);
    $type = strtolower(trim($b_data['book_type'])); 

    if($current_issued_count >= $max_limit) {
        echo "<script>alert('❌ તમારી લિમિટ પૂરી થઈ ગઈ છે!');</script>";
    } elseif($type == 'premium' && $is_premium_user == 0) {
        echo "<script>alert('🔒 આ પ્રીમિયમ બુક છે!'); window.location='subscribe.php';</script>";
    } else {
        $sql = "INSERT INTO issued_books (user_id, book_id, issue_date, status) VALUES ('$u_id', '$book_id', NOW(), 'issued')";
        if(mysqli_query($conn, $sql)) {
            echo "<script>alert('✅ બુક સફળતાપૂર્વક ઇશ્યૂ થઈ ગઈ!'); window.location='reading_history.php';</script>";
        }
    }
}
?>

<style>
    body { 
        background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=1920'); 
        background-size: cover; background-attachment: fixed; color: white; font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .main-wrapper { display: flex; height: 82vh; margin: 20px; gap: 20px; }

    /* ડાબી બાજુનું સાઇડબાર - Scrollable */
    .sidebar-card { 
        width: 280px; background: rgba(15, 23, 42, 0.95); border-radius: 15px; border: 1px solid rgba(255,255,255,0.1);
        display: flex; flex-direction: column; padding: 20px;
    }
    .sidebar-card h4 { color: #fbbf24; margin-bottom: 20px; font-weight: bold; text-align: center; }
    
    .cat-scroll-area { overflow-y: auto; flex-grow: 1; padding-right: 5px; }
    .cat-scroll-area::-webkit-scrollbar { width: 4px; }
    .cat-scroll-area::-webkit-scrollbar-thumb { background: #4361ee; border-radius: 10px; }

    .cat-item {
        background: rgba(255,255,255,0.08); padding: 12px 15px; border-radius: 10px; margin-bottom: 10px;
        cursor: pointer; transition: 0.3s; border-left: 4px solid transparent; font-size: 0.95rem;
    }
    .cat-item:hover, .cat-item.active { background: #4361ee; border-left: 4px solid #fbbf24; transform: scale(1.02); }

    /* જમણી બાજુનું સેક્શન */
    .content-card { 
        flex: 1; background: rgba(0,0,0,0.4); backdrop-filter: blur(10px); border-radius: 15px; 
        padding: 40px; display: flex; flex-direction: column; align-items: center; border: 1px solid rgba(255,255,255,0.1);
    }

    .search-input {
        width: 100%; max-width: 450px; padding: 12px 25px; border-radius: 30px; border: 1px solid rgba(255,255,255,0.2);
        background: rgba(255,255,255,0.1); color: white; outline: none; margin-bottom: 30px; text-align: center;
    }

    .issue-box { width: 100%; max-width: 500px; display: none; animation: fadeIn 0.4s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .modern-dropdown { width: 100%; padding: 15px; border-radius: 12px; margin-bottom: 20px; font-weight: bold; font-size: 1rem; }
    .btn-submit { padding: 15px; border-radius: 12px; border: none; width: 100%; font-weight: bold; font-size: 1.1rem; cursor: pointer; transition: 0.3s; }

    /* નવું સફેદ ફૂટર (White Footer) */
    .white-footer {
        background: #ffffff; padding: 20px 0; text-align: center; color: #333333;
        font-weight: 500; border-top: 1px solid #eeeeee; width: 100%;
    }
    .dev-name { color: #4361ee; font-weight: 700; }
</style>

<div class="main-wrapper">
    <div class="sidebar-card">
        <h4>Categories</h4>
        <div class="cat-scroll-area">
            <?php 
            $cats = mysqli_query($conn, "SELECT DISTINCT category FROM books");
            while($c = mysqli_fetch_assoc($cats)) {
                echo "<div class='cat-item' onclick='showBooks(\"".$c['category']."\", this)'>
                        ".$c['category']."
                      </div>";
            }
            ?>
        </div>
    </div>

    <div class="content-card">
        <input type="text" class="search-input" placeholder="🔍 બુક કે કેટેગરી શોધો...">

        <div id="welcome-info" class="text-center">
            <h2 style="color: #fbbf24;">Welcome, <?php echo $u_name; ?>!</h2>
            <p>તમારી હાલની ઇશ્યૂ કરેલી બુક: <b><?php echo $current_issued_count; ?> / <?php echo $max_limit; ?></b></p>
            <p class="opacity-75">ડાબી બાજુની કેટેગરી પર ક્લિક કરો.</p>
        </div>

        <div class="issue-box" id="form-container">
            <h3 id="selected-cat" class="mb-4 text-warning"></h3>
            <form method="POST">
                <div id="type-badge" style="padding: 10px; border-radius: 10px; margin-bottom: 15px; font-weight: bold; display: none;"></div>
                <select name="book_id" id="book-select" class="modern-dropdown" required onchange="updateBtn()"></select>
                <button type="submit" name="issue" class="btn-submit" id="main-btn">બુક મેળવો 🚀</button>
            </form>
        </div>
    </div>
</div>

<footer class="white-footer">
    <p class="mb-0">
        © 2025 My Library Project | Developed by Muchhdiya Mahek
    </p>
</footer>

<script>
const books = <?php 
    $b_res = mysqli_query($conn, "SELECT id, title, category, book_type FROM books");
    $data = [];
    while($r = mysqli_fetch_assoc($b_res)) { $data[] = $r; }
    echo json_encode($data); 
?>;
const isPremium = <?php echo $is_premium_user; ?>;

function showBooks(category, el) {
    document.querySelectorAll('.cat-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('welcome-info').style.display = 'none';
    document.getElementById('form-container').style.display = 'block';
    document.getElementById('selected-cat').innerText = "Category: " + category;

    const select = document.getElementById('book-select');
    select.innerHTML = '<option value="" disabled selected>-- પુસ્તક પસંદ કરો --</option>';

    books.filter(b => b.category === category).forEach(book => {
        let opt = document.createElement('option');
        opt.value = book.id;
        opt.dataset.type = book.book_type.toLowerCase().trim();
        opt.text = (opt.dataset.type === 'premium' ? "⭐ " : "📖 ") + book.title;
        select.appendChild(opt);
    });
    updateBtn();
}

function updateBtn() {
    const select = document.getElementById('book-select');
    const badge = document.getElementById('type-badge');
    const btn = document.getElementById('main-btn');
    if (select.selectedIndex <= 0) { badge.style.display = "none"; return; }

    const type = select.options[select.selectedIndex].dataset.type;
    badge.style.display = "block";

    if (type === 'premium') {
        if (isPremium === 1) {
            badge.innerHTML = "✨ Premium Book (Unlocked)"; badge.style.background = "#d1fae5"; badge.style.color = "#065f46";
            btn.innerHTML = "Get Premium Book 🔓"; btn.style.background = "#10b981"; btn.style.color = "white";
        } else {
            badge.innerHTML = "🔒 Premium Book (Subscription Needed)"; badge.style.background = "#fee2e2"; badge.style.color = "#991b1b";
            btn.innerHTML = "Upgrade Now 🔒"; btn.style.background = "#ef4444"; btn.style.color = "white";
        }
    } else {
        badge.innerHTML = "📖 Free Book"; badge.style.background = "#fef3c7"; badge.style.color = "#92400e";
        btn.innerHTML = "Get Free Book 🚀"; btn.style.background = "#fbbf24"; btn.style.color = "black";
    }
}
</script>