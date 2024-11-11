<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accountName = $_POST['account_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Store the email, hashed password, and account name in users.txt
    $userData = $accountName . ":" . $email . ":" . password_hash($password, PASSWORD_DEFAULT) . "\n";
    file_put_contents("users.txt", $userData, FILE_APPEND);

    $_SESSION['name'] = $accountName;
    echo "Name saved in session: " . $_SESSION['name'];
    // Redirect to menu page
    header("Location: menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="authStyles.css">
</head>
<body>
<div class="form-container">
    <h2>Register</h2>
    <form method="POST" action="register.php">
        <label for="account_name">Adventurer's Name:</label>
        <input type="text" id="account_name" name="account_name" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <button type="submit">Register</button>
    </form>
    <button onclick="window.location.href='index.php'" class="back-button">Back</button>
</div>
</body>
</html>
