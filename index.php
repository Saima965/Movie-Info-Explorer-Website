<?php
session_start();
require 'config.php';

$msg = "";

// Checking if registration page is requested
$show_register = isset($_GET['register']);

// Display message once
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

// Validation Functions
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isValidUsername($username) {
    return preg_match('/^[A-Za-z][A-Za-z0-9]{2,}$/', $username);
}

function isValidPassword($password) {
    return strlen($password) >= 4 && preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);
}

// regitration
if (isset($_POST['register'])) {
    $username = sanitizeInput($_POST['username']);
    $email    = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);

    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['msg'] = "❌ All fields are required!";
        header("Location: index.php?register=1"); exit;
    }
    if (!isValidUsername($username)) {
        $_SESSION['msg'] = "❌ Username must start with a letter and be at least 3 characters (letters and numbers only).";
        header("Location: index.php?register=1"); exit;
    }
    if (!isValidEmail($email)) {
        $_SESSION['msg'] = "❌ Please enter a valid email address.";
        header("Location: index.php?register=1"); exit;
    }
    if (!isValidPassword($password)) {
        $_SESSION['msg'] = "❌ Password must be at least 4 characters and contain at least one special character.";
        header("Location: index.php?register=1"); exit;
    }

    // password hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'customer')");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "✅ Registration successful! You can now login.";
        header("Location: index.php"); exit;
    } else {
        $_SESSION['msg'] = "❌ Error: " . $stmt->error;
        header("Location: index.php?register=1"); exit;
    }
    $stmt->close();
}

// login work
if (isset($_POST['login'])) {
    $username_or_email = sanitizeInput($_POST['username_or_email']);
    $password = sanitizeInput($_POST['password']);

    if (empty($username_or_email) || empty($password)) {
        $_SESSION['msg'] = "❌ All fields are required!";
        header("Location: index.php"); exit;
    }

    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? OR email=? LIMIT 1");
    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == 'admin') header("Location: admin_dashboard.php");
            elseif ($row['role'] == 'editor') header("Location: editor_dashboard.php");
            else header("Location: customer_dashboard.php");
            exit;
        } else {
            $_SESSION['msg'] = "❌ Invalid username/email or password.";
            header("Location: index.php"); exit;
        }
    } else {
        $_SESSION['msg'] = "❌ Invalid username/email or password.";
        header("Location: index.php"); exit;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User System</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<?php if (!$show_register): ?>
    <!-- Login Form -->
    <h2>Login</h2>
    <form id="loginForm" method="POST">
        Username or Email: 
        <input type="text" name="username_or_email" required><br>
        Password: 
        <input type="password" name="password" required><br>
        <input type="submit" name="login" value="Login"><br>
        <p>Don't have an account? <a href="?register=1">Register here</a></p>

        <?php if($msg): ?><p class="error-msg"><?php echo $msg; ?></p><?php endif; ?>
    </form>

<?php else: ?>
    <!-- Registration Form -->
    <h2>Customer Registration</h2>
    <form id="registerForm" method="POST">
        Username: 
        <input type="text" name="username" required><br>
        Email: 
        <input type="email" name="email" required><br>
        Password: 
        <input type="password" name="password" required><br>
        <input type="submit" name="register" value="Register"><br>
        <p>Already have an account? <a href="index.php">Login here</a></p>

        <?php if($msg): ?><p class="error-msg"><?php echo $msg; ?></p><?php endif; ?>
    </form>
<?php endif; ?>

</body>
</html>
