<?php
session_start();
require 'config.php';
include('includes/header.php');

// Pagination setup
$posts_per_page = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $posts_per_page;

// Get community posts without likes/comments columns
list($posts, $total_pages) = getCommunityPosts($conn, $posts_per_page, $offset);

function getCommunityPosts($conn, $per_page, $offset) {
    try {
        // Count total posts for pagination
        $count_result = $conn->query("SELECT COUNT(*) as total FROM community_posts");
        if (!$count_result) {
            throw new Exception("Count query failed: " . $conn->error);
        }
        $total_data = $count_result->fetch_assoc();
        $total_posts = $total_data['total'];
        $total_pages = ceil($total_posts / $per_page);

        // Get paginated posts
        $stmt = $conn->prepare("
            SELECT id, title, content, image_url, created_at
            FROM community_posts
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
        ");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ii", $per_page, $offset);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        $result = $stmt->get_result();
        $posts = $result->fetch_all(MYSQLI_ASSOC);
        return [$posts, $total_pages];
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [[], 1]; // Return empty posts array and safe total pages
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Using your existing CSS/JS includes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Ensure posts' images have a uniform aspect ratio */
        .post-image {
            width: 100%;
            height: 300px; /* Fixed height for consistent aspect ratio */
            object-fit: cover;
            border-radius: 8px;
            margin: 1rem 0;
        }

        /* Adjust layout for user info and title */
        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .post-header .user-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #e5e7eb; /* Tailwind's gray-300 */
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }

        .post-header .user-name {
            font-size: 1rem;
            font-weight: 600;
            color: #374151; /* Tailwind's gray-700 */
        }

        .post-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937; /* Tailwind's gray-800 */
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h1 class="text-3xl font-bold text-center mb-6">Community Posts</h1>

            <!-- Posts List -->
            <div class="space-y-6">
                <?php foreach ($posts as $post): ?>
                    <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                        <!-- Post Header -->
                        <div class="post-header">
                            <div class="user-icon">
                                <i class="fas fa-user text-gray-600 text-xl"></i>
                            </div>
                            <div>
                                <div class="user-name">Anonymous User</div>
                                <div class="post-title"><?= htmlspecialchars($post['title']) ?></div>
                            </div>
                        </div>

                        <!-- Post Image -->
                        <?php if ($post['image_url']): ?>
                            <img src="<?= htmlspecialchars($post['image_url']) ?>" class="post-image" alt="Post image">
                        <?php endif; ?>

                        <!-- Post Content -->
                        <p class="text-gray-800 mb-4"><?= nl2br(htmlspecialchars($post['content'])) ?></p>

                        <!-- Post Date -->
                        <div class="text-sm text-gray-500">
                            <?= date('M j, Y g:i A', strtotime($post['created_at'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="community.php?page=<?= $i ?>" class="px-4 py-2 mx-1 <?= $page == $i ? 'bg-blue-500 text-white' : 'bg-gray-200' ?> rounded-lg">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <?php include('includes/footer.php'); ?>
</body>
</html>
