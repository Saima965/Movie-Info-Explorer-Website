<?php
session_start();
require 'config.php';

// 🔒 Restrict access to editors only
if (!isset($_SESSION['user']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'editor') {
    $_SESSION['error'] = "You must log in as an editor to access this page.";
    header("Location: index.php");
    exit;
}

// ✅ Check if movie ID is provided
if (!isset($_GET['id'])) {
    header("Location: editor_dashboard.php");
    exit;
}

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM movie WHERE id=$id");
$movie = mysqli_fetch_assoc($result);

// If movie ID is invalid or not found
if (!$movie) {
    header("Location: editor_dashboard.php");
    exit;
}

// -------------------- UPDATE MOVIE --------------------
if (isset($_POST['update_movie'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);

    $imageName = $movie['image']; // keep old image by default

    // If new image uploaded
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $newImage = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $newImage;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // delete old image if exists
            if (!empty($movie['image']) && file_exists("uploads/" . $movie['image'])) {
                unlink("uploads/" . $movie['image']);
            }
            $imageName = $newImage;
        }
    }

    // ✅ Update in movie table
    mysqli_query($conn, "UPDATE movie 
        SET title='$title', genre='$genre', description='$desc', image='$imageName' 
        WHERE id=$id");

    // ✅ EXTRA CHANGE: Sync update in favorite table too
    mysqli_query($conn, "UPDATE favorite 
        SET title='$title', genre='$genre', description='$desc', image='$imageName' 
        WHERE movie_id=$id");

    // Redirect back to editor dashboard
    header("Location: editor_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Movie</title>
    <link rel="stylesheet" href="css/editor_update_movies.css">
</head>
<body>
<?php include 'ed_header.php'; ?> <!-- Editor header -->

<div class="form-container">
    <h2>Edit Movie</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>" required>

        <label>Genre:</label>
        <select name="genre" required>
            <?php
            $genres = ['Action','Adventure','Comedy','Romantic','Horror'];
            foreach($genres as $g){
                $selected = ($movie['genre'] == $g) ? 'selected' : '';
                echo "<option value='$g' $selected>$g</option>";
            }
            ?>
        </select>

        <label>Description:</label>
        <textarea name="description" rows="4" required><?php echo htmlspecialchars($movie['description']); ?></textarea>

        <div class="current-image">
            <p>Current Image:</p>
            <?php if (!empty($movie['image']) && file_exists("uploads/".$movie['image'])): ?>
                <img src="uploads/<?php echo $movie['image']; ?>" alt="Movie Poster">
            <?php else: ?>
                <p>No Image</p>
            <?php endif; ?>
        </div>

        <label>Upload New Image:</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" name="update_movie">Update Movie</button>
    </form>
</div>

</body>
<?php include 'footer.php'; ?>
</html>
