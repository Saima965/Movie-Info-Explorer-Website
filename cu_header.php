<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Movie Info Website</title>

  <!-- Custom CSS for Header -->
  <link rel="stylesheet" href="css/ad_header.css">

  <!-- Font Awesome Free CDN for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<header class="header">
  <!-- Logo -->
  <a href="#" class="logo">
    <i class="fa-solid fa-tv" style="color: #ffffff;"></i> CineScope
  </a>
  
  <!-- Navigation Links -->
  <nav class="navbar">
    <a href="customer_dashboard.php">Home</a>
    <a href="customer_watchlist.php">Watchlist</a>
  </nav>
  
  <div class="icons">
    <!-- Welcome message beside profile icon -->
    <span class="welcome-msg">Welcome, 
      <?php echo isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']) : 'customer'; ?>
    </span>

    <!-- Search icon -->
    <div class="fa fa-search" id="search-btn"></div>
   
    <!-- Profile image button -->
    <div id="login-btn">
      <?php
      // Default profile image
      $defaultProfile = 'images/profile.png';
      $uploadDir = 'uploads/profile_images/';
      $profileImage = $defaultProfile;

      // Use uploaded profile image if exists
      if(isset($_SESSION['user'])){
          $customerUser = $_SESSION['user'];
          $query = mysqli_query($conn, "SELECT profileimage FROM users WHERE username='$customerUser'");
          $row = mysqli_fetch_assoc($query);

          if(!empty($row['profileimage']) && file_exists($uploadDir . $row['profileimage'])){
              $profileImage = $uploadDir . $row['profileimage'];
          }
      }
      ?>
      <img src="<?php echo $profileImage; ?>" alt="Profile" 
           style="width:35px;height:35px;border-radius:50%;object-fit:cover;cursor:pointer;">
    </div>
  </div>
  
  <!-- -------------------- Search Form -------------------- -->
  <form class="search-form" method="GET" action="">
    <!-- Input is empty by default; JS will handle clearing -->
    <input type="text" id="search-box" name="keyword" placeholder="Search Here...." required>
    
    <!-- Optional hidden input for context -->
    <input type="hidden" name="context" value="<?php echo isset($search_context) ? $search_context : ''; ?>">
    
    <!-- Search icon inside form triggers submission -->
    <label for="search-box" class="fa fa-search" onclick="this.closest('form').submit()"></label>
  </form> 
   
  <!-- -------------------- Profile Dropdown -------------------- -->
  <form action="#" class="profile">
    <a href="customer_profile.php" class="box">Profile</a><br>
    <a href="customer_logout.php" class="box" style="color:#fff;">Logout</a>
  </form>
</header>

<!-- Link to JS file -->
<script src="js/ad_header.js"></script>
