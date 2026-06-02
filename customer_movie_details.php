<?php
session_start();
require 'config.php';

// Get current logged-in customer
$customer = $_SESSION['user'];

// Get movie ID from URL
$movie_id = intval($_GET['id']);

// Fetch movie details from database
$movie_query = mysqli_query($conn, "SELECT * FROM movie WHERE id=$movie_id");
$movie = mysqli_fetch_assoc($movie_query);

// -------------------- Add / Update Rating --------------------
if (isset($_POST['rating_submit'])) {
    $rating = intval($_POST['rating']);

    // Check if customer already rated this movie
    $check_rating = mysqli_query($conn, "SELECT * FROM ratings WHERE movie_id=$movie_id AND username='$customer'");

    if (mysqli_num_rows($check_rating) > 0) {
        // Update existing rating
        mysqli_query($conn, "UPDATE ratings SET rating=$rating WHERE movie_id=$movie_id AND username='$customer'");
    } else {
        // Insert new rating
        mysqli_query($conn, "INSERT INTO ratings(movie_id, username, rating) VALUES($movie_id, '$customer', $rating)");
    }

    // Redirect to same page to prevent resubmission
    header("Location: customer_movie_details.php?id=$movie_id");
    exit;
}

// -------------------- Add Comment --------------------
if (isset($_POST['review_submit'])) {
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    mysqli_query($conn, "INSERT INTO comments(movie_id, username, comment) VALUES($movie_id, '$customer', '$comment')");
    header("Location: customer_movie_details.php?id=$movie_id");
    exit;
}

// Fetch all comments for this movie
$comments = mysqli_query($conn, "SELECT * FROM comments WHERE movie_id=$movie_id ORDER BY id DESC");

// Fetch average rating + user’s rating
$avg_rating_res = mysqli_query($conn, "SELECT AVG(rating) AS avg_rating FROM ratings WHERE movie_id=$movie_id");
$avg_rating = mysqli_fetch_assoc($avg_rating_res)['avg_rating'];

$user_rating_res = mysqli_query($conn, "SELECT rating FROM ratings WHERE movie_id=$movie_id AND username='$customer'");
$user_rating = (mysqli_num_rows($user_rating_res) > 0) ? mysqli_fetch_assoc($user_rating_res)['rating'] : 0;

// Check if movie is already in customer's Watchlist
$fav_check = mysqli_query($conn, "SELECT * FROM favorite WHERE customer_email='$customer' AND movie_id=$movie_id");
$is_fav = mysqli_num_rows($fav_check) > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['title']); ?> - Movie Details</title>
    
    <!-- Movie details page CSS -->
    <link rel="stylesheet" href="css/customer_movie_details.css">

    <!-- INLINE CSS TO HIDE SEARCH ICON -->
    <style>
        /* Hide the search icon in header only on movie details page */
        body.movie-details-page .fa-search#search-btn {
            display: none !important;
        }
    </style>
</head>

<body class="movie-details-page">

<?php include 'cu_header.php'; ?> <!-- Include common header -->

<div class="movie-page-container">
    <!-- Movie Image Section -->
    <div class="movie-image">
        <?php if($movie['image']): ?>
            <img src="uploads/<?php echo $movie['image']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
        <?php else: ?>
            <div class="no-image">No Image</div>
        <?php endif; ?>
    </div>

    <!-- Movie Details Section -->
    <div class="movie-details">
        <h2><?php echo htmlspecialchars($movie['title']); ?></h2>
        <p><strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($movie['description']); ?></p>

        <!-- Rating Form -->
        <h3>Give Rating</h3>
        <form method="POST" class="rating-form">
            <label>Rating (1-5):</label>
            <select name="rating" required>
                <option value="">Select</option>
                <?php for($i=1; $i<=5; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php if($user_rating==$i) echo "selected"; ?>>
                        <?php echo str_repeat("⭐", $i); ?>
                    </option>
                <?php endfor; ?>
            </select>
            <button type="submit" name="rating_submit">
                <?php echo ($user_rating > 0) ? "Update Rating" : "Submit Rating"; ?>
            </button>
        </form>

        <!-- Show Average Rating -->
        <?php if($avg_rating > 0): ?>
            <p><strong>Average Rating:</strong> <?php echo round($avg_rating,1); ?> ⭐</p>
        <?php else: ?>
            <p>No ratings yet.</p>
        <?php endif; ?>

        <!-- Review Form -->
        <h3>Add Comment / Review</h3>
        <form method="POST" class="review-form">
            <textarea name="comment" required placeholder="Write your review..."></textarea><br>
            <button type="submit" name="review_submit">Submit Review</button>
        </form>

        <!-- Display All Comments -->
        <h3>All Comments</h3>
        <div class="reviews">
            <?php if(mysqli_num_rows($comments) == 0): ?>
                <p>No comments yet.</p>
            <?php else: ?>
                <?php while($c = mysqli_fetch_assoc($comments)): ?>
                    <div class="review-item">
                        <strong><?php echo htmlspecialchars($c['username']); ?></strong>
                        <p><?php echo htmlspecialchars($c['comment']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
