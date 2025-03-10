<?php
include('config.php');
include('includes/header.php');


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
    <title>Meal Match - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
   
    <main class="max-w-6xl mx-auto p-6 text-center">
        <h2 class="text-4xl font-extrabold text-teal-700">Welcome to Meal Match!</h2>
        <p class="text-lg text-gray-700 mt-2">Your ultimate destination for delicious recipes and meal inspiration.</p>
        <a href="recipes.php" class="mt-4 inline-block bg-blue-500 text-white font-semibold px-6 py-3 rounded-lg hover:bg-blue-600 transition">Explore Recipes</a>
    </main>

    <?php include('includes/footer.php'); ?>
</body>

</html>

