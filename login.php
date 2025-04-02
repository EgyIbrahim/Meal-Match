<?php
session_start();
include('config.php');
include('includes/header.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare SQL query to prevent SQL injection
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check if the password is hashed
        if (password_verify($password, $row['password_hash'])) {
            // Password is correct
            $_SESSION['username'] = $username; // Store session data
            $_SESSION['user_id'] = $row['id']; // Store user ID in session
            header("Location: index.php"); // Redirect to home page
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Username does not exist!";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Meal Match</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center mb-4">Login to Meal Match</h2>
            <?php if (isset($error)): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700">Username</label>
                    <input type="text" name="username" class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Password</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded-lg transition">Login</button>
            </form>
            <p class="text-center text-gray-600 mt-4">Don't have an account? <a href="signup.php" class="text-blue-500">Register here</a></p>
        </div>
    </div>
    <?php include('includes/footer.php'); ?>
</body>
</html>