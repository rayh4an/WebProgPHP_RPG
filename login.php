<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $authenticated = false;

    // Check credentials in users.txt
    $users = file("users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($users as $user) {
        list($storedAccountName, $storedEmail, $storedHash) = explode(":", $user);
        if ($email == $storedEmail && password_verify($password, $storedHash)) {
            $authenticated = true;
            break;
        }
    }

    if ($authenticated) {
        if (isset($_POST['remember'])) {
            setcookie("email", $email, time() + (86400 * 30), "/"); // Expires in 30 days
        }
        header("Location: menu.php");
        exit();
    } else {
        echo "Invalid credentials!";
    }
} else if (isset($_COOKIE["email"])) {
    $email = $_COOKIE["email"];
} else {
    $email = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="authStyles.css">
</head>
<body>
<div class="form-container">
    <h2>Login</h2>
    <form method="POST" action="login.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <label>
            <input type="checkbox" name="remember" <?php if ($email) echo "checked"; ?>> Remember me
        </label><br><br>
        
        <button type="submit">Sign In</button>
    </form>
    <button onclick="window.location.href='index.php'" class="back-button">Back</button>
</div>
</body>
</html>

