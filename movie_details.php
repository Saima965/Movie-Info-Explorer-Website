<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user']) || $_SESSION['role']!=='admin'){ 
    header("Location:index.php"); 
    exit; 
}

function esc($conn,$v){ return mysqli_real_escape_string($conn,$v); }

$uploadDir = __DIR__.'/uploads/';
if(!is_dir($uploadDir)) mkdir($uploadDir,0755,true);

// -------------------- ADD MOVIE --------------------
if(isset($_POST['add_movie'])){
    $title = esc($conn,$_POST['title']);
    $genre = esc($conn,$_POST['genre']);
    $desc  = esc($conn,$_POST['description']);
    $imageFileName = null;
    if(isset($_FILES['image']) && $_FILES['image']['error']===UPLOAD_ERR_OK){
        $fileTmp=$_FILES['image']['tmp_name'];
        $ext=strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));
        $imageFileName=time().'_'.uniqid().'.'.$ext;
        move_uploaded_file($fileTmp,$uploadDir.$imageFileName);
    }
    $img_q=$imageFileName? "'".$imageFileName."'":"NULL";
    mysqli_query($conn,"INSERT INTO movie(title,genre,description,image,added_by) VALUES('$title','$genre','$desc',$img_q,'admin')");
}

// -------------------- EDIT MOVIE (fetch for form) --------------------
$editMovie = null;
if(isset($_GET['edit'])){
    $id=intval($_GET['edit']);
    $editMovie=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM movie WHERE id=$id"));
}

// -------------------- UPDATE MOVIE --------------------
if(isset($_POST['update_movie'])){
    $id=intval($_POST['id']);
    $title = esc($conn,$_POST['title']);
    $genre = esc($conn,$_POST['genre']);
    $desc  = esc($conn,$_POST['description']);
    $row=mysqli_fetch_assoc(mysqli_query($conn,"SELECT image FROM movie WHERE id=$id"));
    $imageFileName=$row['image'];

    // If new image uploaded
    if(isset($_FILES['image']) && $_FILES['image']['error']===UPLOAD_ERR_OK){
        $fileTmp=$_FILES['image']['tmp_name'];
        $ext=strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));
        $newImage=time().'_'.uniqid().'.'.$ext;
        move_uploaded_file($fileTmp,$uploadDir.$newImage);
        if(!empty($imageFileName) && file_exists($uploadDir.$imageFileName)) @unlink($uploadDir.$imageFileName);
        $imageFileName=$newImage;
    }

    $img_q=$imageFileName? "'".$imageFileName."'":"NULL";

    // ✅ Update in movie table
    mysqli_query($conn,"UPDATE movie 
        SET title='$title', genre='$genre', description='$desc', image=$img_q 
        WHERE id=$id");

    // ✅ EXTRA CHANGE: Sync update in favorite table too
    mysqli_query($conn,"UPDATE favorite 
        SET title='$title', genre='$genre', description='$desc', image=$img_q 
        WHERE movie_id=$id");

    header("Location: movie_details.php"); 
    exit;
}

// -------------------- DELETE MOVIE --------------------
if(isset($_GET['delete'])){
    $id=intval($_GET['delete']);
    $row=mysqli_fetch_assoc(mysqli_query($conn,"SELECT image FROM movie WHERE id=$id"));
    if(!empty($row['image']) && file_exists($uploadDir.$row['image'])) @unlink($uploadDir.$row['image']);
    mysqli_query($conn,"DELETE FROM movie WHERE id=$id");
    header("Location: movie_details.php"); exit;
}

// -------------------- SEARCH HANDLING --------------------
$keyword = isset($_GET['keyword']) ? esc($conn, $_GET['keyword']) : '';
$searchQuery = $keyword ? "WHERE title LIKE '%$keyword%'" : '';
$movies=mysqli_query($conn,"SELECT * FROM movie $searchQuery");

$genres = ['Adventure','Thriller','Horror','Romantic','Comedy','Action'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Movie Details</title>
<link rel="stylesheet" href="css/movie_details.css">
<link rel="stylesheet" href="css/ad_header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php include 'ad_header.php'; ?> <!-- Common admin header -->

<div class="container">
    <!-- Movie Form -->
    <div class="form-section">
        <h3 style="margin-bottom: 20px;">
            <?php echo $editMovie ? 'Edit Movie' : 'Add Movie'; ?>
        </h3>
        <form method="POST" enctype="multipart/form-data">
            <?php if($editMovie): ?>
            <input type="hidden" name="id" value="<?php echo $editMovie['id']; ?>">
            <?php endif; ?>

            Title: <input type="text" name="title" value="<?php echo $editMovie ? htmlspecialchars($editMovie['title']) : ''; ?>" required><br>

            Genre:
            <select name="genre">
                <?php foreach($genres as $g): ?>
                <option value="<?php echo $g; ?>" <?php echo $editMovie && $editMovie['genre']==$g ? 'selected' : ''; ?>><?php echo $g; ?></option>
                <?php endforeach; ?>
            </select><br>

            Description: 
            <textarea name="description" rows="4" required><?php echo $editMovie ? htmlspecialchars($editMovie['description']) : ''; ?></textarea><br>

            Image: <input type="file" name="image"><br>
            <?php if($editMovie && $editMovie['image']): ?>
            Current Image: <img src="uploads/<?php echo $editMovie['image']; ?>" width="50"><br>
            <?php endif; ?>

            <input type="submit" name="<?php echo $editMovie?'update_movie':'add_movie'; ?>" value="<?php echo $editMovie?'Update':'Add'; ?>">
            <?php if($editMovie): ?><a href="movie_details.php">Cancel</a><?php endif; ?>
        </form>
    </div>

    <!-- Movie Table -->
    <div class="table-section">
        <h3 style="margin-bottom: 20px;">All Movies <?php if($keyword) echo "(Search: $keyword)"; ?></h3>
        <table border="1">
            <tr><th>ID</th><th>Title</th><th>Genre</th><th>Description</th><th>Image</th><th>Action</th></tr>
            <?php while($m=mysqli_fetch_assoc($movies)): ?>
            <tr>
                <td><?php echo $m['id']; ?></td>
                <td><?php echo htmlspecialchars($m['title']); ?></td>
                <td><?php echo htmlspecialchars($m['genre']); ?></td>
                <td><?php echo htmlspecialchars($m['description']); ?></td>
                <td><?php echo $m['image']?'<img src="uploads/'.$m['image'].'" width="50">':'No image'; ?></td>
                <td>
                    <a href="?edit=<?php echo $m['id']; ?>">Edit</a> |
                    <a href="?delete=<?php echo $m['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php if(mysqli_num_rows($movies)==0): ?>
            <tr><td colspan="6" style="text-align:center;">No movies found</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>

</body>
</html>
