<?php
session_start();
require 'config.php';
include('includes/header.php');

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Get current user data
$user_query = "SELECT * FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
$user_result->free();

if (!$user) {
    die("User not found in the database.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Update Profile (including profile picture)
    if (isset($_POST['update_profile'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $bio = trim($_POST['bio']);
        
        // Initialize with the current profile picture value from the database
        $profile_picture = $user['profile_picture'];
        
        // Check if a file was uploaded
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $fileName = $_FILES['profile_picture']['name'];
            $fileTmp = $_FILES['profile_picture']['tmp_name'];
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                // Generate a unique file name and store it in the uploads folder
                $newFileName = uniqid() . '.' . $ext;
                $uploadPath = 'uploads/' . $newFileName;
                if (move_uploaded_file($fileTmp, $uploadPath)) {
                    // Save the file path in the database's profile_picture column
                    $profile_picture = $uploadPath;
                } else {
                    $error = "Error uploading file.";
                }
            } else {
                $error = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
            }
        }
        
        if (empty($error)) {
            if (!empty($username) && !empty($email)) {
                // Check if username or email already exists for another user
                $check_query = "SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bind_param("ssi", $username, $email, $user_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                
                if ($check_result->num_rows === 0) {
                    // Update the user's profile information, including the profile_picture field
                    $update_query = "UPDATE users SET username = ?, email = ?, bio = ?, profile_picture = ? WHERE id = ?";
                    $update_stmt = $conn->prepare($update_query);
                    $update_stmt->bind_param("ssssi", $username, $email, $bio, $profile_picture, $user_id);
                    
                    if ($update_stmt->execute()) {
                        $success = "Profile updated successfully!";
                    } else {
                        $error = "Error updating profile. Please try again.";
                    }
                } else {
                    $error = "Username or email already exists.";
                }
                $check_result->free();
            } else {
                $error = "Username and email cannot be empty.";
            }
        }
    }
    
    // Update Password Section
    if (isset($_POST['update_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (password_verify($current_password, $user['password_hash'])) {
            if ($new_password === $confirm_password) {
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $update_query = "UPDATE users SET password_hash = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("si", $new_hash, $user_id);
                
                if ($update_stmt->execute()) {
                    $success = "Password updated successfully!";
                } else {
                    $error = "Error updating password. Please try again.";
                }
            } else {
                $error = "New passwords do not match.";
            }
        } else {
            $error = "Current password is incorrect.";
        }
    }
    
    // Refresh user data after updates
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user = $user_result->fetch_assoc();
    $user_result->free();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings - Meal Match</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-8 text-center">User Settings</h1>
        
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <!-- Profile Settings Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6">Profile Settings</h2>
            <form action="settings.php" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" id="username" 
                           value="<?= htmlspecialchars($user['username']) ?>" 
                           class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" 
                           value="<?= htmlspecialchars($user['email']) ?>" 
                           class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                </div>
                <div class="mb-4">
                    <label for="bio" class="block text-gray-700 mb-2">Bio</label>
                    <textarea name="bio" id="bio" rows="4" 
                              class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200"><?= htmlspecialchars($user['bio']) ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="profile_picture" class="block text-gray-700 mb-2">Profile Picture</label>
                    <input type="file" name="profile_picture" id="profile_picture" 
                           class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                    <?php if (!empty($user['profile_picture'])): ?>
                        <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" class="mt-2 h-16 w-16 rounded-full">
                    <?php endif; ?>
                </div>
                <button type="submit" name="update_profile" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition">
                    Update Profile
                </button>
            </form>
        </div>
        
        <!-- Account Settings Section (Password Update) -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Account Settings</h2>
            <form action="settings.php" method="POST">
                <div class="mb-4">
                    <label for="current_password" class="block text-gray-700 mb-2">Current Password</label>
                    <input type="password" name="current_password" id="current_password" 
                           class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                </div>
                <div class="mb-4">
                    <label for="new_password" class="block text-gray-700 mb-2">New Password</label>
                    <input type="password" name="new_password" id="new_password" 
                           class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="block text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" 
                           class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                </div>
                <button type="submit" name="update_password" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition">
                    Change Password
                </button>
            </form>
        </div>
    </div>
    
    <?php include('includes/footer.php'); ?>
</body>
</html>
