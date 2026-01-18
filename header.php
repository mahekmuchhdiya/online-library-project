<!DOCTYPE html>
<html>
<head>
    <title>Online Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Library Pro</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="books.php">All Books</a></li>
        
        <li class="nav-item"><a class="nav-link" href="issue_book.php">Issue Book</a></li>
        <li class="nav-item"><a class="nav-link" href="Reading_history.php">Reading History</a></li>
        
		<li class="nav-item">
		<a class="nav-link" href="profile.php">My Profile</a>
		</li>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item"><a class="nav-link btn btn-danger btn-sm text-white ms-2" href="logout.php">Logout</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>