<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user']) || $_SESSION['role']!=='admin'){ 
    header("Location:index.php"); exit; 
}

function esc($conn,$v){ return mysqli_real_escape_string($conn,$v); }

// Delete user
if(isset($_GET['del_user'])){
    $id=intval($_GET['del_user']);
    mysqli_query($conn,"DELETE FROM users WHERE id=$id");
    header("Location: manage_users.php");
    exit;
}

// Edit user
if(isset($_POST['update_user'])){
    $id       = intval($_POST['id']);
    $username = esc($conn,$_POST['username']);
    $email    = esc($conn,$_POST['email']);
    $role     = esc($conn,$_POST['role']);
    mysqli_query($conn,"UPDATE users SET username='$username', email='$email', role='$role' WHERE id=$id");
    header("Location: manage_users.php");
    exit;
}

// Search functionality
$search_keyword = isset($_GET['keyword']) ? esc($conn, $_GET['keyword']) : '';

$customers = $search_keyword != '' 
    ? mysqli_query($conn,"SELECT * FROM users WHERE role='customer' AND username LIKE '%$search_keyword%'")
    : mysqli_query($conn,"SELECT * FROM users WHERE role='customer'");

$editors = $search_keyword != '' 
    ? mysqli_query($conn,"SELECT * FROM users WHERE role='editor' AND username LIKE '%$search_keyword%'")
    : mysqli_query($conn,"SELECT * FROM users WHERE role='editor'");

$admins = $search_keyword != '' 
    ? mysqli_query($conn,"SELECT * FROM users WHERE role='admin' AND username LIKE '%$search_keyword%'")
    : mysqli_query($conn,"SELECT * FROM users WHERE role='admin'");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Users</title>
<link rel="stylesheet" href="css/manage_users.css">
<link rel="stylesheet" href="css/ad_header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>
<body>

<?php include 'ad_header.php'; ?>

<div class="container">

<h2>Manage Customers</h2>
<table>
<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th></tr>
<?php while($c=mysqli_fetch_assoc($customers)): ?>
<tr>
<td><?php echo $c['id'];?></td>
<td><?php echo htmlspecialchars($c['username']);?></td>
<td><?php echo htmlspecialchars($c['email']);?></td>
<td><?php echo htmlspecialchars($c['role']);?></td>
<td>
    <form class="inline-form" method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $c['id'];?>">
        <input type="text" name="username" value="<?php echo htmlspecialchars($c['username']);?>" required>
        <input type="text" name="email" value="<?php echo htmlspecialchars($c['email']);?>" required>
        <select name="role">
            <option value="customer" <?php echo $c['role']=='customer'?'selected':'';?>>Customer</option>
            <option value="editor" <?php echo $c['role']=='editor'?'selected':'';?>>Editor</option>
            <option value="admin" <?php echo $c['role']=='admin'?'selected':'';?>>Admin</option>
        </select>
        <input type="submit" name="update_user" value="Update">
    </form>
    <a href="?del_user=<?php echo $c['id'];?>" onclick="return confirm('Are you sure to delete?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>

<h2>Manage Editors</h2>
<table>
<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th></tr>
<?php while($e=mysqli_fetch_assoc($editors)): ?>
<tr>
<td><?php echo $e['id'];?></td>
<td><?php echo htmlspecialchars($e['username']);?></td>
<td><?php echo htmlspecialchars($e['email']);?></td>
<td><?php echo htmlspecialchars($e['role']);?></td>
<td>
    <form class="inline-form" method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $e['id'];?>">
        <input type="text" name="username" value="<?php echo htmlspecialchars($e['username']);?>" required>
        <input type="text" name="email" value="<?php echo htmlspecialchars($e['email']);?>" required>
        <select name="role">
            <option value="customer" <?php echo $e['role']=='customer'?'selected':'';?>>Customer</option>
            <option value="editor" <?php echo $e['role']=='editor'?'selected':'';?>>Editor</option>
            <option value="admin" <?php echo $e['role']=='admin'?'selected':'';?>>Admin</option>
        </select>
        <input type="submit" name="update_user" value="Update">
    </form>
    <a href="?del_user=<?php echo $e['id'];?>" onclick="return confirm('Are you sure to delete?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>

<h2>Manage Admins</h2>
<table>
<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th></tr>
<?php while($a=mysqli_fetch_assoc($admins)): ?>
<tr>
<td><?php echo $a['id'];?></td>
<td><?php echo htmlspecialchars($a['username']);?></td>
<td><?php echo htmlspecialchars($a['email']);?></td>
<td><?php echo htmlspecialchars($a['role']);?></td>
<td>
    <form class="inline-form" method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $a['id'];?>">
        <input type="text" name="username" value="<?php echo htmlspecialchars($a['username']);?>" required>
        <input type="text" name="email" value="<?php echo htmlspecialchars($a['email']);?>" required>
        <select name="role">
            <option value="customer" <?php echo $a['role']=='customer'?'selected':'';?>>Customer</option>
            <option value="editor" <?php echo $a['role']=='editor'?'selected':'';?>>Editor</option>
            <option value="admin" <?php echo $a['role']=='admin'?'selected':'';?>>Admin</option>
        </select>
        <input type="submit" name="update_user" value="Update">
    </form>
    <a href="?del_user=<?php echo $a['id'];?>" onclick="return confirm('Are you sure to delete?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>

</div>
<?php include 'footer.php'; ?>

<script src="header.js"></script>
</body>
</html>
