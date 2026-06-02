<?php
session_start();
require 'config.php';

// ---------------------- Access Control ----------------------
if(!isset($_SESSION['user']) || $_SESSION['role']!=='editor'){ 
    header("Location:index.php"); 
    exit; 
}

// ---------------------- Search Handling ----------------------
$searchKeyword = '';
if(isset($_GET['keyword'])){
    $searchKeyword = mysqli_real_escape_string($conn, $_GET['keyword']);
}

// ---------------------- Fetch favorite Movies ----------------------
// Query to get number of users who saved each movie
$sql = "
    SELECT f.movie_id, f.title AS movie_name, f.genre, f.image, COUNT(f.customer_email) AS saved_count
    FROM favorite f
";

// Apply search filter if keyword is provided
if(!empty($searchKeyword)){
    $sql .= " WHERE f.title LIKE '%$searchKeyword%'";
}

// Group by movie_id to count number of users per movie
$sql .= " GROUP BY f.movie_id ORDER BY saved_count DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editor - favorite Movies</title>
    <link rel="stylesheet" type="text/css" href="css/admin_reviews.css">
    <style>
        table img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
    </style>
</head>
<body>
<?php include 'ed_header.php'; ?> <!-- Include common header -->

<div class="container">
    <h2>Editor Panel - favorite Movies</h2>

    <?php if(!empty($searchKeyword)): ?>
        <p>Showing results for: <strong><?php echo htmlspecialchars($searchKeyword); ?></strong></p>
    <?php endif; ?>

    <table>
        <tr>
            <th>Movie ID</th>
            <th>Title</th>
            <th>Genre</th>
            <th>Poster</th>
            <th>Saved by Users</th>
        </tr>

        <?php if(mysqli_num_rows($result) == 0): ?>
            <tr><td colspan="5">No favorite movies found.</td></tr>
        <?php else: ?>
            <?php while($row = mysqli_fetch_assoc($result)){ ?>
            <tr>
                <td><?php echo $row['movie_id']; ?></td>
                <td><?php echo htmlspecialchars($row['movie_name']); ?></td>
                <td><?php echo htmlspecialchars($row['genre']); ?></td>
                <td>
                    <?php if(!empty($row['image']) && file_exists('uploads/'.$row['image'])): ?>
                        <img src="<?php echo 'uploads/'.$row['image']; ?>" alt="Movie Poster">
                    <?php else: ?>
                        <img src="images/default_movie.png" alt="Default Poster">
                    <?php endif; ?>
                </td>
                <td><?php echo $row['saved_count']; ?></td>
            </tr>
            <?php } ?>
        <?php endif; ?>
    </table>
</div>
</body>
<?php include 'footer.php'; ?>
</html>
