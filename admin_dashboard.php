<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user']) || $_SESSION['role']!=='admin'){ 
    header("Location:index.php"); 
    exit; 
}

// Handle search keyword
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

// Fetch all distinct genres
$genres_res = mysqli_query($conn,"SELECT DISTINCT genre FROM movie");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="css/admin_dashboard.css">
</head>
<body>

<?php include 'ad_header.php'; ?>

<div class="admin-dashboard">

<?php while($g=mysqli_fetch_assoc($genres_res)):
    $genre = $g['genre'];

    // Fetch movies in this genre, filtered by keyword if given
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

    if(mysqli_num_rows($movies) > 0):
?>
    <h3><?php echo htmlspecialchars($genre); ?></h3>
    <div class="movies-container">
        <?php while($m=mysqli_fetch_assoc($movies)): ?>
        <div class="movie-card">
            <?php if($m['image']): ?>
                <img src="uploads/<?php echo $m['image']; ?>" class="movie-image" alt="movie poster"><br>
            <?php endif; ?>
            <strong><?php echo htmlspecialchars($m['title']); ?></strong><br>
            <em><?php echo htmlspecialchars($m['genre']); ?></em><br>
            <a href="movie_details.php?id=<?php echo $m['id']; ?>" class="details-link">Details</a>
        </div>
        <?php endwhile; ?>
    </div>
<?php 
    endif;
endwhile; 
?>

<?php if($keyword !== '' && mysqli_num_rows($genres_res) == 0){
    echo "<p class='no-results'>No movies found for '$keyword'.</p>";
} ?>

</div> <!-- End admin-dashboard -->
<?php include 'footer.php'; ?>

</body>
</html>
