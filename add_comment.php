<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $post_id = (int)$_POST['post_id'];
    $content = trim($_POST['comment_content']);
    $user_id = (int)$_SESSION['user_id'];

    if (!empty($content)) {
        // Insert comment
        $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $post_id, $user_id, $content);
        $stmt->execute();

        // Get user info
        $user = $conn->query("SELECT username, profile_picture FROM users WHERE id = $user_id")->fetch_assoc();
        
        // Get new comment count
        $count = $conn->query("SELECT COUNT(*) as cnt FROM comments WHERE post_id = $post_id")->fetch_assoc()['cnt'];

        // Build avatar HTML
        $avatar = !empty($user['profile_picture']) 
            ? '<img src="'.htmlspecialchars($user['profile_picture']).'" class="w-8 h-8 rounded-full mr-2">'
            : '<div class="w-8 h-8 rounded-full mr-2 bg-gray-300 flex items-center justify-center"><i class="fas fa-user text-gray-600 text-sm"></i></div>';

        echo json_encode([
            'success' => true,
            'new_count' => $count,
            'username' => $user['username'],
            'content' => htmlspecialchars($content),
            'avatar' => $avatar
        ]);
        exit;
    }
}

echo json_encode(['success' => false]);