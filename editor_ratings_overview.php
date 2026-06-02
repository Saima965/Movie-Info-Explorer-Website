<?php
// Start session if not already started
session_start();
require 'config.php';

// ---------------------- Access Control ----------------------
// Only allow editors to access this page
if(!isset($_SESSION['user']) || $_SESSION['role']!=='editor'){ 
    header("Location:index.php"); 
    exit; 
}

// ---------------------- Search Handling ----------------------
// Get search keyword from the search form
$searchKeyword = '';
if(isset($_GET['keyword'])){
    // Escape special characters to prevent SQL injection
    $searchKeyword = mysqli_real_escape_string($conn, $_GET['keyword']);
}

// ---------------------- Fetch All Ratings ----------------------
// Query ratings joined with movie names
$sql = "
    SELECT r.id, r.movie_id, r.username, r.rating, m.title AS movie_name
    FROM ratings r
    LEFT JOIN movie m ON r.movie_id = m.id
";

// Add search filter if keyword is provided
if(!empty($searchKeyword)){
    $sql .= " WHERE m.title LIKE '%$searchKeyword%'";
}

// Order by latest rating first
$sql .= " ORDER BY r.id DESC";
$ratings = mysqli_query($conn, $sql);

// ---------------------- Fetch Rating Statistics ----------------------
// Query average rating and count of each star for each movie
$stats_sql = "
    SELECT 
        r.movie_id,
        m.title AS movie_name,
        COUNT(*) AS total_ratings,
        ROUND(AVG(r.rating),2) AS avg_rating,
        SUM(r.rating=1) AS star_1,
        SUM(r.rating=2) AS star_2,
        SUM(r.rating=3) AS star_3,
        SUM(r.rating=4) AS star_4,
        SUM(r.rating=5) AS star_5
    FROM ratings r
    LEFT JOIN movie m ON r.movie_id = m.id
";

// Apply search filter for stats table as well
if(!empty($searchKeyword)){
    $stats_sql .= " WHERE m.title LIKE '%$searchKeyword%'";
}

// Group results by movie
$stats_sql .= " GROUP BY r.movie_id";

$stats_res = mysqli_query($conn, $stats_sql);
$stats = [];
while($row = mysqli_fetch_assoc($stats_res)){
    $stats[$row['movie_id']] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editor - Ratings</title>
    <link rel="stylesheet" type="text/css" href="css/admin_reviews.css">
</head>
<body>

<?php include 'ed_header.php'; ?> <!-- Include common header -->

<div class="container">
    <h2>Editor Panel - Ratings</h2>

    <!-- Show search keyword if used -->
    <?php if(!empty($searchKeyword)): ?>
        <p>Showing results for: <strong><?php echo htmlspecialchars($searchKeyword); ?></strong></p>
    <?php endif; ?>

    <!-- ---------------------- Table 1: All Ratings ---------------------- -->
    <h3>All Ratings</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Movie ID</th>
            <th>Movie Name</th> <!-- Added Movie Name -->
            <th>Customer</th>
            <th>Rating</th>
        </tr>
        <?php if(mysqli_num_rows($ratings) == 0): ?>
            <tr><td colspan="5">No ratings found.</td></tr>
        <?php else: ?>
            <?php while($r = mysqli_fetch_assoc($ratings)){ ?>
            <tr>
                <td><?php echo $r['id']; ?></td>
                <td><?php echo $r['movie_id']; ?></td>
                <td><?php echo htmlspecialchars($r['movie_name']); ?></td>
                <td><?php echo htmlspecialchars($r['username']); ?></td>
                <td><?php echo $r['rating']; ?> ⭐</td>
            </tr>
            <?php } ?>
        <?php endif; ?>
    </table>

    <!-- ---------------------- Table 2: Average Ratings ---------------------- -->
    <h3>Average Rating per Movie</h3>
    <table>
        <tr>
            <th>Movie ID</th>
            <th>Movie Name</th>
            <th>Average Rating</th>
        </tr>
        <?php if(empty($stats)): ?>
            <tr><td colspan="3">No ratings found.</td></tr>
        <?php else: ?>
            <?php foreach($stats as $ar){ ?>
            <tr>
                <td><?php echo $ar['movie_id']; ?></td>
                <td><?php echo htmlspecialchars($ar['movie_name']); ?></td>
                <td><?php echo $ar['avg_rating']; ?> ⭐</td>
            </tr>
            <?php } ?>
        <?php endif; ?>
    </table>

    <!-- ---------------------- Table 3: Rating Distribution ---------------------- -->
    <h3>Rating Distribution per Movie</h3>
    <table>
        <tr>
            <th>Movie ID</th>
            <th>Movie Name</th>
            <th>1⭐</th>
            <th>2⭐</th>
            <th>3⭐</th>
            <th>4⭐</th>
            <th>5⭐</th>
            <th>Total Ratings</th>
        </tr>
        <?php if(empty($stats)): ?>
            <tr><td colspan="8">No ratings found.</td></tr>
        <?php else: ?>
            <?php foreach($stats as $s){ ?>
            <tr>
                <td><?php echo $s['movie_id']; ?></td>
                <td><?php echo htmlspecialchars($s['movie_name']); ?></td>
                <td><?php echo $s['star_1']; ?></td>
                <td><?php echo $s['star_2']; ?></td>
                <td><?php echo $s['star_3']; ?></td>
                <td><?php echo $s['star_4']; ?></td>
                <td><?php echo $s['star_5']; ?></td>
                <td><?php echo $s['total_ratings']; ?></td>
            </tr>
            <?php } ?>
        <?php endif; ?>
    </table>

</div>
<?php include 'footer.php'; ?>
</body>
</html>
