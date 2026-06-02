<?php
session_start();
require 'config.php';

// Ensure customer is logged in
if(!isset($_SESSION['user']) || $_SESSION['role']!=='customer'){
    header("Location: index.php");
    exit;
}

$customer = $_SESSION['user'];

// Handle Add to Watchlist
if(isset($_POST['add_watchlist'])){
    $movie_id = intval($_POST['movie_id']);
    
    $movie_res = mysqli_query($conn,"SELECT * FROM movie WHERE id=$movie_id");
    $movie = mysqli_fetch_assoc($movie_res);

    if($movie){
        mysqli_query($conn,"
            INSERT IGNORE INTO favorite(customer_email,movie_id,title,genre,description,image) 
            VALUES(
                '$customer',
                {$movie['id']},
                '".mysqli_real_escape_string($conn,$movie['title'])."',
                '".mysqli_real_escape_string($conn,$movie['genre'])."',
                '".mysqli_real_escape_string($conn,$movie['description'])."',
                '".mysqli_real_escape_string($conn,$movie['image'])."'
            )
        ");
    }

    header("Location: customer_dashboard.php");
    exit;
}

// -------------------- SEARCH HANDLING --------------------
// Get the search keyword from header form
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

// Fetch genres
$genres_res = mysqli_query($conn,"SELECT DISTINCT genre FROM movie");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Home</title>
<link rel="stylesheet" href="css/customer_dashboard.css">
</head>
<body>

<?php include 'cu_header.php'; ?>

<div class="customer-dashboard">

<?php while($g=mysqli_fetch_assoc($genres_res)):
    $genre = $g['genre'];

    // -------------------- FILTER MOVIES BY SEARCH KEYWORD --------------------
    if($keyword !== ''){
        $safe_keyword = mysqli_real_escape_string($conn, $keyword);
        $movies = mysqli_query($conn,"
            SELECT * FROM movie 
            WHERE genre='".mysqli_real_escape_string($conn,$genre)."' 
            AND (title LIKE '%$safe_keyword%' OR genre LIKE '%$safe_keyword%')
        ");
    } else {
        $movies = mysqli_query($conn,"SELECT * FROM movie WHERE genre='".mysqli_real_escape_string($conn,$genre)."'");
    }
?>
    <h3><?php echo htmlspecialchars($genre); ?></h3>
    <div class="movies-container">
        <?php while($m=mysqli_fetch_assoc($movies)):
            $fav_check = mysqli_query($conn,"SELECT * FROM favorite WHERE customer_email='$customer' AND movie_id=".$m['id']);
            $is_fav = mysqli_num_rows($fav_check) > 0;
        ?>
        <div class="movie-card">
            <?php if($m['image']): ?>
                <img src="uploads/<?php echo $m['image']; ?>" class="movie-image"><br>
            <?php endif; ?>
            <strong><?php echo htmlspecialchars($m['title']); ?></strong><br>
            <em><?php echo htmlspecialchars($m['genre']); ?></em><br>
            <a href="customer_movie_details.php?id=<?php echo $m['id']; ?>">View Details</a><br>

            <?php if(!$is_fav): ?>
                <form method="POST" class="watchlist-form">
                    <input type="hidden" name="movie_id" value="<?php echo $m['id']; ?>">
                    <button type="submit" name="add_watchlist">Add to Watchlist</button>
                </form>
            <?php else: ?>
                <span class="favorited">Favorited</span>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>
<?php endwhile; ?>

<!-- Optional: show message if no movies found at all -->
<?php
if($keyword !== '' && mysqli_num_rows($genres_res) == 0){
    echo "<p class='no-results'>No movies found for '$keyword'.</p>";
}
?>

</div> <!-- End customer-dashboard -->
<?php include 'footer.php'; ?>
</body>
</html>
