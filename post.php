<?php
session_start();
require 'config.php';
include('includes/header.php');

// Check if post ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: community.php");
    exit;
}

$post_id = (int)$_GET['id'];

// Get post details
$post_query = "SELECT cp.*, u.username, 
               (SELECT COUNT(*) FROM likes WHERE post_id = cp.post_id) AS likes_count
               FROM community_posts cp
               JOIN users u ON cp.user_id = u.id
               WHERE cp.post_id = ?";
$post_stmt = $conn->prepare($post_query);
$post_stmt->bind_param("i", $post_id);
$post_stmt->execute();
$post_result = $post_stmt->get_result();

// Check if post exists
if ($post_result->num_rows === 0) {
    header("Location: community.php");
    exit;
}

$post = $post_result->fetch_assoc();

// Get comments for this post
$comments_query = "SELECT c.*, u.username 
                 FROM comments c
                 JOIN users u ON c.user_id = u.id
                 WHERE c.post_id = ?
                 ORDER BY c.created_at DESC";
$comments_stmt = $conn->prepare($comments_query);
$comments_stmt->bind_param("i", $post_id);
$comments_stmt->execute();
$comments_result = $comments_stmt->get_result();

// Handle new comment submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id']) && isset($_POST['comment_submit'])) {
    $comment_content = trim($_POST['comment_content']);
    
    if (!empty($comment_content)) {
        $insert_comment = "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)";
        $comment_stmt = $conn->prepare($insert_comment);
        $comment_stmt->bind_param("iis", $post_id, $_SESSION['user_id'], $comment_content);
        
        if ($comment_stmt->execute()) {
            header("Location: post.php?id=" . $post_id);
            exit;
        } else {
            $error = "Error posting comment. Please try again.";
        }
    } else {
        $error = "Comment cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?> - Meal Match Community</title>
    <style>
        .post-content {
            max-height: 70vh;
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Post Content -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8 post-content">
            <div class="mb-2 flex justify-between items-center">
                <a href="community.php" class="text-blue-500 hover:text-blue-700">← Back to Community</a>
                <span class="text-sm text-gray-500"><?= date('M j, Y g:i A', strtotime($post['created_at'])) ?></span>
            </div>
            
            <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($post['title']) ?></h1>
            <p class="text-gray-600 mb-4">Posted by: <span class="font-medium"><?= htmlspecialchars($post['username']) ?></span></p>
            
            <?php if (!empty($post['image_url'])): ?>
                <img src="<?= htmlspecialchars($post['image_url']) ?>" class="w-full h-64 object-cover mb-4 rounded-lg" alt="Post image">
            <?php endif; ?>
            
            <div class="prose max-w-none border-t pt-4">
                <?= nl2br(htmlspecialchars($post['content'])) ?>
            </div>
            
            <div class="mt-4 flex items-center space-x-4">
                <span class="text-red-500">❤️ <?= $post['likes_count'] ?> Likes</span>
                <span class="text-blue-500">💬 <?= $post['comments_count'] ?? 0 ?> Comments</span>
            </div>
        </div>
        
        <!-- Comments Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Comments</h2>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <!-- New Comment Form -->
                <div class="mb-8 bg-gray-50 p-4 rounded-lg">
                    <?php if (isset($error)): ?>
                        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="post.php?id=<?= $post_id ?>" method="POST">
                        <div class="mb-4">
                            <label for="comment_content" class="block text-gray-700 mb-2">Leave a comment</label>
                            <textarea name="comment_content" id="comment_content" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200" required></textarea>
                        </div>
                        <button type="submit" name="comment_submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition">Post Comment</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="bg-yellow-50 p-4 rounded-lg mb-6">
                    <p class="text-center">Please <a href="login.php" class="text-blue-500 font-semibold">login</a> to leave a comment.</p>
                </div>
            <?php endif; ?>
            
            <!-- Comments List -->
            <div class="space-y-6">
                <?php if ($comments_result->num_rows > 0): ?>
                    <?php while ($comment = $comments_result->fetch_assoc()): ?>
                        <div class="border-b pb-4 last:border-b-0">
                            <div class="flex justify-between items-start">
                                <span class="font-medium"><?= htmlspecialchars($comment['username']) ?></span>
                                <span class="text-sm text-gray-500"><?= date('M j, Y g:i A', strtotime($comment['created_at'])) ?></span>
                            </div>
                            <p class="mt-2"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center py-8">
                        <p class="text-gray-500">No comments yet. Be the first to share your thoughts!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include('includes/footer.php'); ?>
</body>
</html>