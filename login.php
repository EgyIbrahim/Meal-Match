<?php
session_start();
include('config.php');
include('includes/header.php');
    
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Prepare SQL query to prevent SQL injection
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username; // Store session data
            header("Location: welcome.php"); // Redirect after successful login
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
<body>
    <!-- Flex container for centering -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <!-- Login form card -->
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold text-center mb-4">Login to Meal Match</h2>
            
            <?php if(isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
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