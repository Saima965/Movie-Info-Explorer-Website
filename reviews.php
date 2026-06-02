<?php
session_start(); // Start PHP session
require 'config.php'; // Include database connection

// -------------------- ACCESS CONTROL --------------------
// Only allow admin users to access this page
if(!isset($_SESSION['user']) || $_SESSION['role']!=='admin'){ 
    header("Location:index.php"); // Redirect non-admin users
    exit; 
}

// -------------------- DELETE COMMENT --------------------
// Check if 'del_comment' is in URL to delete a specific comment
if(isset($_GET['del_comment'])){
    $id = intval($_GET['del_comment']); // Convert ID to integer for security
    mysqli_query($conn, "DELETE FROM comments WHERE id=$id"); // Execute delete query
}

// -------------------- SEARCH FUNCTIONALITY --------------------
$search_keyword = ''; // Initialize search keyword variable
if(isset($_GET['keyword']) && !empty(trim($_GET['keyword']))){
    // Escape input to prevent SQL injection
    $search_keyword = mysqli_real_escape_string($conn, trim($_GET['keyword']));

    // Fetch comments where username matches the search keyword
    $comments = mysqli_query($conn, "SELECT * FROM comments WHERE username LIKE '%$search_keyword%' ORDER BY id DESC");
} else {
    // If no search keyword, fetch all comments
    $comments = mysqli_query($conn, "SELECT * FROM comments ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin - Comments</title>
    <!-- Link to external CSS for comments table styling -->
    <link rel="stylesheet" type="text/css" href="css/admin_reviews.css">
</head>
<body>

<?php include 'ad_header.php'; ?> <!-- Include the common admin header -->

<div class="container">
    <h2>Admin Panel - Manage Comments</h2>

    <!-- -------------------- COMMENTS TABLE -------------------- -->
    <h3>Comments</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Movie ID</th>
            <th>Customer</th>
            <th>Comment</th>
            <th>Action</th>
        </tr>

        <!-- -------------------- SEARCH RESULT INFO INSIDE TABLE -------------------- -->
        <?php if($search_keyword != ''): ?>
        <tr>
            <td colspan="5" style="text-align:center; font-weight:bold; background-color:#f0f0f0;">
                Showing results for: "<?php echo htmlspecialchars($search_keyword); ?>"
            </td>
        </tr>
        <?php endif; ?>

        <!-- -------------------- NO COMMENTS FOUND -------------------- -->
        <?php if(mysqli_num_rows($comments) == 0): ?>
        <tr>
            <td colspan="5" style="text-align:center;">No comments found.</td>
        </tr>
        <?php else: ?>
            <!-- -------------------- LOOP THROUGH COMMENTS -------------------- -->
            <?php while($c = mysqli_fetch_assoc($comments)){ ?>
            <tr>
                <td><?php echo $c['id']; ?></td> <!-- Comment ID -->
                <td><?php echo $c['movie_id']; ?></td> <!-- Movie ID related to comment -->
                <td><?php echo htmlspecialchars($c['username']); ?></td> <!-- Username of customer -->
                <td><?php echo htmlspecialchars($c['comment']); ?></td> <!-- Actual comment -->
                <!-- Delete link with confirmation popup -->
                <td>
                    <a href="?del_comment=<?php echo $c['id']; ?>" onclick="return confirm('Delete this comment?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        <?php endif; ?>
    </table>
</div>

<?php include 'footer.php'; ?> <!-- Include footer file -->

</body>
</html>
