<?php
// Start the session
session_start();

// Include the database configuration
include('config.php');
// Check if the recipe ID is provided in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: recipes.php"); // Redirect if no ID is provided
    exit();
}

// Get the recipe ID from the URL
$recipeId = (int)$_GET['id'];

// Fetch the recipe details from the database
$sql = "SELECT 
            r.*, 
            u.username AS author, 
            GROUP_CONCAT(c.name SEPARATOR ', ') AS categories
        FROM recipes r
        LEFT JOIN users u ON r.user_id = u.id
        LEFT JOIN recipe_category rc ON r.id = rc.recipe_id
        LEFT JOIN categories c ON rc.category_id = c.id
        WHERE r.id = ?
        GROUP BY r.id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $recipeId);
$stmt->execute();
$result = $stmt->get_result();

// Check if the recipe exists
if ($result->num_rows === 0) {
    header("Location: recipes.php"); // Redirect if recipe doesn't exist
    exit();
}

// Fetch the recipe data
$recipe = $result->fetch_assoc();

// Set the page title dynamically
$pageTitle = $recipe['title'] . " - Meal Match";

// Include the header
include('includes/header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
    <main class="max-w-4xl mx-auto p-6">
        <!-- Recipe Image -->
        <img src="<?= htmlspecialchars($recipe['image']) ?>" 
             alt="<?= htmlspecialchars($recipe['title']) ?>" 
             class="w-full h-64 object-cover rounded-lg mb-6">

        <!-- Recipe Title -->
        <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($recipe['title']) ?></h1>

        <!-- Recipe Metadata -->
        <div class="mb-6 text-gray-600">
            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded">
                <?= htmlspecialchars($recipe['categories']) ?>
            </span>
            <div class="mt-2">
                Posted by <span class="font-semibold"><?= htmlspecialchars($recipe['author']) ?></span> on 
                <?= date('F j, Y', strtotime($recipe['created_at'])) ?>
            </div>
        </div>

        <!-- Ingredients -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-2xl font-semibold mb-4">Ingredients</h2>
            <div class="whitespace-pre-wrap"><?= htmlspecialchars($recipe['ingredients']) ?></div>
        </div>

        <!-- Steps -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-4">Instructions</h2>
            <div class="whitespace-pre-wrap"><?= htmlspecialchars($recipe['steps']) ?></div>
        </div>
    </main>

    <!-- Include the footer -->
    <?php include('includes/footer.php'); ?>
</body>
</html>