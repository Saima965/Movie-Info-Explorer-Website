<?php
require 'config.php';
include 'cu_header.php'; // Common header for customer pages

$customer = $_SESSION['user']; // Logged-in customer username

// -------------------- REMOVE FROM WATCHLIST --------------------
if(isset($_POST['remove_fav'])){
    $movie_id_to_remove = intval($_POST['movie_id']);
    mysqli_query($conn, "DELETE FROM favorite WHERE customer_email='$customer' AND movie_id=$movie_id_to_remove");
    header("Location: customer_watchlist.php"); // refresh page to update list
    exit;
}

// -------------------- SEARCH HANDLING --------------------
// Get the search keyword from header search form
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

if($keyword){
    // Sanitize the keyword to prevent SQL injection
    $safe_keyword = mysqli_real_escape_string($conn, $keyword);

    // Fetch movies from 'favorite' table filtered by title or genre
    $fav_res = mysqli_query($conn, "
        SELECT * FROM favorite 
        WHERE customer_email='$customer' 
        AND (title LIKE '%$safe_keyword%' OR genre LIKE '%$safe_keyword%')
    ");
} else {
    // No search keyword, fetch all favorite movies
    $fav_res = mysqli_query($conn,"SELECT * FROM favorite WHERE customer_email='$customer'");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Watchlist</title>
  <link rel="stylesheet" href="css/customer_watchlist.css"> <!-- External CSS -->
</head>
<body>

<div class="watchlist-wrapper">
  <h2 class="watchlist-title">My Watchlist</h2>

  <?php if(mysqli_num_rows($fav_res) > 0): ?>
    <div class="watchlist-container">
      <?php while($m = mysqli_fetch_assoc($fav_res)): ?>
        <div class="movie-card">
          <!-- Show movie image if exists -->
          <?php if($m['image']): ?>
            <img src="uploads/<?php echo $m['image']; ?>" class="movie-img">
          <?php endif; ?>

          <!-- Display movie title and genre -->
          <strong class="movie-title"><?php echo htmlspecialchars($m['title']); ?></strong>
          <em class="movie-genre"><?php echo htmlspecialchars($m['genre']); ?></em>

          <!-- Link to movie details page -->
          <a href="customer_movie_details.php?id=<?php echo $m['movie_id']; ?>" class="details-link">
            View Details
          </a>

          <!-- Remove from Watchlist Button -->
          <form method="POST" style="margin-top:10px;">
              <input type="hidden" name="movie_id" value="<?php echo $m['movie_id']; ?>">
              <button type="submit" name="remove_fav" class="remove-btn">Remove</button>
          </form>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <!-- Show message if no movies match search -->
    <p class="no-results">No movies found<?php echo $keyword ? " for '$keyword'" : ""; ?>.</p>
  <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
