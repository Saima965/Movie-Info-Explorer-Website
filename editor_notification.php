<?php
session_start();
require 'config.php';

// Only editor can access
if(!isset($_SESSION['user']) || $_SESSION['role']!=='editor'){ 
    header("Location:index.php"); 
    exit; 
}

// Get editor username safely
$editorUser = mysqli_real_escape_string($conn, $_SESSION['user']);

// Handle delete request
if(isset($_GET['delete_id'])){
    $deleteId = intval($_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM notification WHERE id=$deleteId AND editor='$editorUser'");
    header("Location: editor_notification.php"); // refresh page after delete
    exit;
}

// Fetch notifications for this editor
$res = mysqli_query($conn, "SELECT * FROM notification WHERE editor='$editorUser' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Notifications</title>
    <!-- Link external CSS -->
    <link rel="stylesheet" href="css/editor_notification.css">
</head>
<body>
<?php include 'ed_header.php'; ?>
<div class="container">
    <h2>My Notifications</h2>

    <?php if(mysqli_num_rows($res) > 0){ ?>
        <?php while($n = mysqli_fetch_assoc($res)){ ?>
            <div class="notif">
                <!-- Notification message -->
                <span class="msg"><?php echo htmlspecialchars($n['message']); ?></span>
                <!-- Notification time -->
                <span class="time"><?php echo $n['created_at']; ?></span>
                <!-- Delete link -->
                <a class="delete-btn" href="editor_notification.php?delete_id=<?php echo $n['id']; ?>" onclick="return confirm('Are you sure to delete this notification?');">Delete</a>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p class="no-notif">No notifications yet.</p>
    <?php } ?>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
