<?php
session_start(); // Start the session to store user data

// Database connection
$servername = "localhost"; // Your server name (usually localhost)
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "meal_match";    // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username exists in the database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the user data
        $row = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Start a session and set user data
            $_SESSION['username'] = $username;
            header("Location: welcome.php"); // Redirect to a welcome page
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Username does not exist!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meal Match Login</title>
    <link rel="stylesheet" href="style.css"> <!-- Optional: You can add your custom styles -->
</head>
<body>

    <div class="login-container">
        <h2>Login to Meal Match</h2>

        <?php if (isset($error)): ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>

</body>
</html>
