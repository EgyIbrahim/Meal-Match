<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $post_id = (int)$_POST['post_id'];
    $user_id = (int)$_SESSION['user_id'];

    // Check existing like
    $check = $conn->prepare("SELECT id FROM likes WHERE post_id = ? AND user_id = ?");
    $check->bind_param("ii", $post_id, $user_id);
    $check->execute();
    
    if ($check->get_result()->num_rows === 0) {
        $insert = $conn->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
        $insert->bind_param("ii", $post_id, $user_id);
        $insert->execute();
    }

    // Get new count
    $count = $conn->query("SELECT COUNT(*) as cnt FROM likes WHERE post_id = $post_id")->fetch_assoc()['cnt'];
    
    echo json_encode(['success' => true, 'new_count' => $count]);
    exit;
}

echo json_encode(['success' => false]);