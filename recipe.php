<?php
session_start();
require 'config.php';
include('includes/header.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validate recipe ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: recipes.php");
    exit();
}

$recipeId = (int)$_GET['id'];

// Fetch recipe details
$sql = "SELECT * FROM recipes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $recipeId);
$stmt->execute();
$result = $stmt->get_result();
$recipe = $result->fetch_assoc();

if (!$recipe) {
    die("Recipe not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($recipe['title']) ?> - Meal Match</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
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
                <?= htmlspecialchars($recipe['category']) ?> | <?= htmlspecialchars($recipe['area']) ?>
            </span>
        </div>

        <!-- Ingredients -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-2xl font-semibold mb-4">Ingredients</h2>
            <ul class="list-disc ml-6">
                <?php
                // Fetch ingredients (fixed query - no quantity column)
                $ingredientSql = "SELECT i.name 
                                  FROM recipe_ingredients ri
                                  JOIN ingredients i ON ri.ingredient_id = i.id
                                  WHERE ri.recipe_id = ?";
                $ingredientStmt = $conn->prepare($ingredientSql);
                $ingredientStmt->bind_param("i", $recipeId);
                $ingredientStmt->execute();
                $ingredientResult = $ingredientStmt->get_result();

                if ($ingredientResult->num_rows > 0) {
                    while ($ingredient = $ingredientResult->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($ingredient['name']) . "</li>";
                    }
                } else {
                    echo "<li>No ingredients listed</li>";
                }
                ?>
            </ul>
        </div>

        <!-- Instructions -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-4">Instructions</h2>
            <?php if (!empty($recipe['steps'])): ?>
                <ol class="list-decimal ml-6 text-gray-700 leading-relaxed">
                    <?php
                    // Split steps correctly (supports "1. Step; 2. Step" format)
                    $steps = preg_split('/\d+\./', $recipe['steps'], -1, PREG_SPLIT_NO_EMPTY);
                    foreach ($steps as $step) {
                        $cleanStep = trim($step);
                        if (!empty($cleanStep)) {
                            echo "<li>" . htmlspecialchars($cleanStep) . "</li>";
                        }
                    }
                    ?>
                </ol>
            <?php else: ?>
                <p class="text-gray-600">No instructions available.</p>
            <?php endif; ?>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>
</body>
</html>