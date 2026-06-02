<?php
session_start();
require 'config.php';

$msg = "";

// Check if admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$admin = $_SESSION['user'];

// Directory to store profile images
$uploadDir = 'uploads/profile_images/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

// Fetch current admin info
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE username='$admin'"));

// Update username
if (isset($_POST['update_username'])) {
    $new_username = $_POST['new_username'];
    mysqli_query($conn, "UPDATE users SET username='$new_username' WHERE username='$admin'");
    $_SESSION['user'] = $new_username; // Update session
    $admin = $new_username;
    $msg = "✅ Username updated!";
}

// Update password
if (isset($_POST['update_password'])) {
    $new_pass = $_POST['new_password'];
    mysqli_query($conn, "UPDATE users SET password='$new_pass' WHERE username='$admin'");
    $msg = "✅ Password updated!";
}

// Update email
if (isset($_POST['update_email'])) {
    $new_email = $_POST['new_email'];
    mysqli_query($conn, "UPDATE users SET email='$new_email' WHERE username='$admin'");
    $msg = "✅ Email updated!";
}

// Upload profile image
if (isset($_POST['upload_image']) && isset($_FILES['profile_image'])) {
    $file = $_FILES['profile_image'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $admin . '.' . $ext; // New image filename
    $target = $uploadDir . $filename;

    // Delete old image if it exists and is not default
    if (!empty($user['profileimage']) && $user['profileimage'] != '' && file_exists($uploadDir . $user['profileimage'])) {
        unlink($uploadDir . $user['profileimage']);
    }

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $target)) {
        mysqli_query($conn, "UPDATE users SET profileimage='$filename' WHERE username='$admin'");
        $msg = "✅ Profile image updated!";
        $user['profileimage'] = $filename; // Update current user data for display
    } else {
        $msg = "❌ Failed to upload image!";
    }
}

// Delete account
if (isset($_POST['delete_account'])) {
    // Delete profile image if exists
    if (!empty($user['profileimage']) && file_exists($uploadDir . $user['profileimage'])) {
        unlink($uploadDir . $user['profileimage']);
    }

    mysqli_query($conn, "DELETE FROM users WHERE username='$admin'");
    session_destroy();
    header("Location: index.php");
    exit;
}

// Refresh user data after updates
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE username='$admin'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile</title>
    <!-- Link to external CSS -->
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
<?php include 'ad_header.php'; ?> <!-- Common admin header -->
<div class="profile-container">
    <h2>Admin Profile Information</h2>

    <!-- Display Profile Image -->
    <div class="profile-image">
        <?php 
        // Default profile image for first-time login
        $profilePath = 'images/profile.png';
        if (!empty($user['profileimage']) && file_exists($uploadDir . $user['profileimage'])) {
            $profilePath = $uploadDir . $user['profileimage'];
        }
        ?>
        <img src="<?php echo $profilePath; ?>" alt="Profile Image">
    </div>

    <!-- Display Username and Email -->
    <p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

    <!-- Update Username Form -->
    <h3>Update Username</h3>
    <form method="POST">
        <input type="text" name="new_username" placeholder="New Username" required>
        <input type="submit" name="update_username" value="Update">
    </form>

    <!-- Update Password Form -->
    <h3>Update Password</h3>
    <form method="POST">
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="submit" name="update_password" value="Update">
    </form>

    <!-- Update Email Form -->
    <h3>Update Email</h3>
    <form method="POST">
        <input type="email" name="new_email" placeholder="New Email" required>
        <input type="submit" name="update_email" value="Update">
    </form>

    <!-- Upload Profile Image Form -->
    <h3>Upload Profile Image</h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="profile_image" accept="image/*" required>
        <input type="submit" name="upload_image" value="Upload">
    </form>

    <!-- Delete Account Form -->
    <h3>Delete Account</h3>
    <form method="POST" class="delete-account" onsubmit="return confirm('Are you sure you want to delete your admin account?');">
        <input type="submit" name="delete_account" value="Delete Account">
    </form>

    <!-- Display Message -->
    <p class="msg"><?php echo $msg; ?></p>
</div>

</body>
</html>
