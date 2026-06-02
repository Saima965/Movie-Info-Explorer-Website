<?php
session_start(); // Start the session to track logged-in user
require 'config.php'; // Include database connection

// Check if user is logged in and has admin role
if(!isset($_SESSION['user']) || $_SESSION['role']!=='admin'){ 
    header("Location:index.php"); // Redirect to homepage if not admin
    exit; // Stop further execution
}

// Function to safely escape user input for SQL queries
function esc($conn,$v){ 
    return mysqli_real_escape_string($conn,$v); 
}

// -------------------- Approve Movie -------------------- //
if(isset($_GET['approve'])){
    $id = intval($_GET['approve']); // Get movie ID from URL and ensure it's an integer
    $res = mysqli_query($conn,"SELECT * FROM pending_movie WHERE id=$id"); // Fetch movie from pending_movie table

    if($res && mysqli_num_rows($res)>0){ // Check if movie exists
        $row = mysqli_fetch_assoc($res); // Get movie data

        // Escape data before inserting into main movie table
        $title = esc($conn,$row['title']);
        $genre = esc($conn,$row['genre']);
        $desc  = esc($conn,$row['description']);
        $img   = !empty($row['image']) ? "'".esc($conn,$row['image'])."'" : "NULL"; // Handle empty image
        $added_by = esc($conn,$row['added_by']); // Editor who submitted the movie

        // Insert approved movie into movie table
        mysqli_query($conn,"INSERT INTO movie(title,genre,description,image,added_by)
                            VALUES('$title','$genre','$desc',$img,'$added_by')") or die(mysqli_error($conn));

        // Delete movie from pending_movie table
        mysqli_query($conn,"DELETE FROM pending_movie WHERE id=$id");

        // Send notification to editor about approval
        $msg = "Your movie \"$title\" has been approved by admin.";
        mysqli_query($conn,"INSERT INTO notification(editor,message) VALUES('$added_by','$msg')") or die(mysqli_error($conn));
    }
}

// -------------------- Reject Movie -------------------- //
if(isset($_GET['reject'])){
    $id = intval($_GET['reject']); // Get movie ID from URL
    $res = mysqli_query($conn,"SELECT * FROM pending_movie WHERE id=$id"); // Fetch movie data

    if($res && mysqli_num_rows($res)>0){ // Check if movie exists
        $row = mysqli_fetch_assoc($res);

        // Delete uploaded image file if exists
        if(!empty($row['image']) && file_exists('uploads/'.$row['image'])){
            @unlink('uploads/'.$row['image']); // Suppress errors with @
        }

        // Delete movie from pending_movie table
        mysqli_query($conn,"DELETE FROM pending_movie WHERE id=$id");

        // Send notification to editor about rejection
        $editorUser = esc($conn,$row['added_by']);
        $msg = "Your movie \"{$row['title']}\" has been rejected by admin.";
        mysqli_query($conn,"INSERT INTO notification(editor,message) VALUES('$editorUser','$msg')") or die(mysqli_error($conn));
    }
}

// -------------------- Fetch all pending movies -------------------- //
$pending = mysqli_query($conn,"SELECT * FROM pending_movie"); // Get all pending movies

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pending Movies</title>
<link rel="stylesheet" href="css/admin_pending_movies.css"> <!-- Link to CSS file -->
</head>
<body>

<?php include 'ad_header.php'; ?> <!-- Include admin header -->

<!-- Content wrapper to prevent overlap with fixed header and footer -->
<div class="content-wrapper">

<h3>Pending Movies</h3> <!-- Page heading -->

<table border="1"> <!-- Table to display pending movies -->
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Genre</th>
    <th>Added By</th>
    <th>Actions</th>
</tr>

<?php while($p=mysqli_fetch_assoc($pending)){ ?> <!-- Loop through all pending movies -->
<tr>
    <td><?php echo $p['id'];?></td> <!-- Movie ID -->
    <td><?php echo htmlspecialchars($p['title']);?></td> <!-- Movie Title -->
    <td><?php echo htmlspecialchars($p['genre']);?></td> <!-- Movie Genre -->
    <td><?php echo htmlspecialchars($p['added_by']);?></td> <!-- Editor username -->
    <td>
        <!-- Action links for approve or reject -->
        <a href="?approve=<?php echo $p['id'];?>" onclick="return confirm('Approve this movie?')">Approve</a> |
        <a href="?reject=<?php echo $p['id'];?>" onclick="return confirm('Reject this movie?')">Reject</a>
    </td>
</tr>
<?php } ?> <!-- End loop -->

</table>

</div> <!-- End of content-wrapper -->

<?php include 'footer.php'; ?> <!-- Include footer -->

</body>
</html>
