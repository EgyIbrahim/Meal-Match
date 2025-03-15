<?php
session_start();
require 'config.php';
include('includes/header.php');

// Handle interactions
handlePostActions();
handleLikeActions();
handleCommentActions();

// Pagination setup
$posts_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

// Get community posts
list($posts, $total_pages) = getCommunityPosts($conn, $posts_per_page, $page, $offset);

function handlePostActions() {
    global $conn;
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
        if (isset($_POST['post_submit'])) {
            // Handle new post submission
            $title = trim($_POST['post_title']);
            $content = trim($_POST['post_content']);
            $image_url = handleImageUpload();

            if (!empty($title) && !empty($content)) {
                $stmt = $conn->prepare("INSERT INTO community_posts (user_id, title, content, image_url) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $_SESSION['user_id'], $title, $content, $image_url);
                $stmt->execute();
                header("Location: community.php?page=1");
                exit;
            }
        }
    }
}

function handleLikeActions() {
    global $conn;
    if (isset($_POST['like_post']) && isset($_SESSION['user_id'])) {
        $post_id = (int)$_POST['post_id'];
        $user_id = (int)$_SESSION['user_id'];
        
        // Check if already liked
        $check = $conn->prepare("SELECT id FROM likes WHERE post_id = ? AND user_id = ?");
        $check->bind_param("ii", $post_id, $user_id);
        $check->execute();
        
        if ($check->get_result()->num_rows == 0) {
            // Add like
            $insert = $conn->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
            $insert->bind_param("ii", $post_id, $user_id);
            $insert->execute();
            
            // Update likes count
            $update = $conn->prepare("UPDATE community_posts SET likes_count = likes_count + 1 WHERE id = ?");
            $update->bind_param("i", $post_id);
            $update->execute();
        }
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit;
    }
}

function handleCommentActions() {
    global $conn;
    if (isset($_POST['add_comment']) && isset($_SESSION['user_id'])) {
        $post_id = (int)$_POST['post_id'];
        $content = trim($_POST['comment_content']);
        
        if (!empty($content)) {
            $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $post_id, $_SESSION['user_id'], $content);
            $stmt->execute();
            
            // Update comments count
            $update = $conn->prepare("UPDATE community_posts SET comments_count = comments_count + 1 WHERE id = ?");
            $update->bind_param("i", $post_id);
            $update->execute();
        }
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit;
    }
}

function handleImageUpload() {
    if (!empty($_FILES['post_image']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["post_image"]["name"]);
        if (move_uploaded_file($_FILES["post_image"]["tmp_name"], $target_file)) {
            return $target_file;
        }
    }
    return null;
}

function getCommunityPosts($conn, $per_page, $page, $offset) {
    // Get total posts
    $count_result = $conn->query("SELECT COUNT(*) as total FROM community_posts");
    $total_posts = $count_result->fetch_assoc()['total'];
    $total_pages = ceil($total_posts / $per_page);

    // Get paginated posts
    $stmt = $conn->prepare("
        SELECT cp.*, u.username, u.profile_picture,
            (SELECT COUNT(*) FROM likes WHERE post_id = cp.id) AS likes_count
        FROM community_posts cp
        JOIN users u ON cp.user_id = u.id
        ORDER BY cp.created_at DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("ii", $per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return [$result->fetch_all(MYSQLI_ASSOC), $total_pages];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Existing head content -->
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h1 class="text-3xl font-bold text-center mb-6">Community Posts</h1>

            <!-- New Post Form (existing with image upload added) -->
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="mb-8 bg-gray-50 p-6 rounded-lg">
                    <form action="community.php" method="POST" enctype="multipart/form-data">
                        <!-- Existing form fields -->
                        <div class="mb-4">
                            <label for="post_image" class="block text-gray-700 mb-2">Upload Image</label>
                            <input type="file" name="post_image" id="post_image" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <!-- Rest of form -->
                    </form>
                </div>
            <?php endif; ?>

            <!-- Posts List -->
            <div class="space-y-6">
                <?php foreach ($posts as $post): ?>
                    <div class="border rounded-lg p-4 hover:shadow-md transition">
                        <!-- Post Header -->
                        <div class="flex items-center mb-4">
                            <img src="<?= htmlspecialchars($post['profile_picture']) ?>" 
                                 class="w-12 h-12 rounded-full mr-3" 
                                 alt="<?= htmlspecialchars($post['username']) ?>'s profile picture">
                            <div>
                                <h3 class="text-xl font-semibold"><?= htmlspecialchars($post['title']) ?></h3>
                                <p class="text-gray-600"><?= htmlspecialchars($post['username']) ?></p>
                            </div>
                        </div>

                        <!-- Post Content -->
                        <?php if ($post['image_url']): ?>
                            <img src="<?= htmlspecialchars($post['image_url']) ?>" 
                                 class="mb-4 rounded-lg max-w-full h-auto" 
                                 alt="Post image">
                        <?php endif; ?>
                        <p class="text-gray-800 mb-4"><?= nl2br(htmlspecialchars($post['content'])) ?></p>

                        <!-- Interaction Buttons -->
                        <div class="flex items-center justify-between border-t pt-4">
                            <div class="flex items-center space-x-4">
                                <form method="POST">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <button type="submit" name="like_post" 
                                            class="flex items-center text-red-500 hover:text-red-600">
                                        ❤️ <?= $post['likes_count'] ?> Likes
                                    </button>
                                </form>
                                
                                <span class="text-blue-500">
                                    💬 <?= $post['comments_count'] ?> Comments
                                </span>
                            </div>
                            <span class="text-sm text-gray-500">
                                <?= date('M j, Y g:i A', strtotime($post['created_at'])) ?>
                            </span>
                        </div>

                        <!-- Comments Section -->
                        <div class="mt-4 pl-4 border-l-2 border-gray-200">
                            <?php
                            $comments = $conn->query("
                                SELECT c.*, u.username, u.profile_picture 
                                FROM comments c
                                JOIN users u ON c.user_id = u.id
                                WHERE post_id = {$post['id']}
                                ORDER BY created_at DESC
                                LIMIT 3
                            ");
                            ?>
                            
                            <?php if ($comments->num_rows > 0): ?>
                                <?php while ($comment = $comments->fetch_assoc()): ?>
                                    <div class="mb-3">
                                        <div class="flex items-center mb-2">
                                            <img src="<?= htmlspecialchars($comment['profile_picture']) ?>" 
                                                 class="w-8 h-8 rounded-full mr-2">
                                            <span class="font-medium"><?= htmlspecialchars($comment['username']) ?></span>
                                        </div>
                                        <p class="text-gray-700 ml-4"><?= htmlspecialchars($comment['content']) ?></p>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif; ?>

                            <!-- Add Comment Form -->
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <form method="POST" class="mt-4">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <div class="flex gap-2">
                                        <input type="text" name="comment_content" 
                                               class="flex-1 border rounded-lg p-2" 
                                               placeholder="Write a comment...">
                                        <button type="submit" name="add_comment" 
                                                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                                            Post
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <!-- Pagination (existing code) -->
            </div>
        </div>
    </div>
    
    <?php include('includes/footer.php'); ?>
</body>
</html>