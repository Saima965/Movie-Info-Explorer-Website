<?php
session_start();
require 'config.php';

// 🔒 Restrict access to editors only
if(!isset($_SESSION['user']) || $_SESSION['role']!=='editor'){ 
    header("Location:index.php"); 
    exit; 
}

function esc($conn, $v){ 
    return mysqli_real_escape_string($conn, $v); 
}

$uploadDir = __DIR__.'/uploads/';
if(!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

// ✅ Handle "Add Pending Movie" form submission
if(isset($_POST['add_movie'])){
    $title = esc($conn, $_POST['title']);
    $genre = esc($conn, $_POST['genre']);
    $desc  = esc($conn, $_POST['description']);
    $imageFileName = null;

    if(isset($_FILES['image']) && $_FILES['image']['error']===UPLOAD_ERR_OK){
        $fileTmp = $_FILES['image']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $imageFileName = time().'_'.uniqid().'.'.$ext;
        move_uploaded_file($fileTmp, $uploadDir.$imageFileName);
    }

    $img_q = $imageFileName ? "'".$imageFileName."'" : "NULL";

    // ✅ Insert editor username into added_by column
    $editorUsername = esc($conn, $_SESSION['user']);
    mysqli_query($conn, "INSERT INTO pending_movie(title,genre,description,image,added_by) 
                         VALUES('$title','$genre','$desc',$img_q,'$editorUsername')") 
                         or die(mysqli_error($conn));

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// 🔎 Search functionality
$keyword = isset($_GET['keyword']) ? esc($conn, $_GET['keyword']) : '';
$searchQuery = $keyword ? "WHERE title LIKE '%$keyword%'" : '';
$movies = mysqli_query($conn, "SELECT * FROM pending_movie $searchQuery");

// Genres
$genres = ['Adventure','Thriller','Horror','Romantic','Comedy','Action'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Editor - Pending Movies</title>
<link rel="stylesheet" href="css/movie_details.css">
<link rel="stylesheet" href="css/ad_header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php include 'ed_header.php'; ?>

<div class="container">

    <div class="form-section">
        <h3>Add Pending Movie</h3>
        <form method="POST" enctype="multipart/form-data">
            Title: <input type="text" name="title" required><br>
            Genre:
            <select name="genre">
                <?php foreach($genres as $g): ?>
                <option value="<?php echo $g; ?>"><?php echo $g; ?></option>
                <?php endforeach; ?>
            </select><br>
            Description: <textarea name="description" rows="4" required></textarea><br>
            Image: <input type="file" name="image"><br>
            <input type="submit" name="add_movie" value="Add">
        </form>
    </div>

    <div class="table-section">
        <h3>All Pending Movies <?php if($keyword) echo "(Search: $keyword)"; ?></h3>
        <table border="1">
            <tr>
                <th>ID</th><th>Title</th><th>Genre</th><th>Description</th><th>Image</th><th>Added By</th>
            </tr>
            <?php while($m = mysqli_fetch_assoc($movies)): ?>
            <tr>
                <td><?php echo $m['id']; ?></td>
                <td><?php echo htmlspecialchars($m['title']); ?></td>
                <td><?php echo htmlspecialchars($m['genre']); ?></td>
                <td><?php echo htmlspecialchars($m['description']); ?></td>
                <td><?php echo $m['image'] ? '<img src="uploads/'.$m['image'].'" width="50">' : 'No image'; ?></td>
                <td><?php echo htmlspecialchars($m['added_by']); ?></td>
            </tr>
            <?php endwhile; ?>
            <?php if(mysqli_num_rows($movies) == 0): ?>
            <tr>
                <td colspan="6" style="text-align:center;">No pending movies found</td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>

</html>
