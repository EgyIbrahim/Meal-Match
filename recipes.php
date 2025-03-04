<?php
include('config.php');
include('includes/header.php');

// Get recipes with categories
$sql = "SELECT r.*, GROUP_CONCAT(c.name SEPARATOR ', ') AS categories 
        FROM recipes r
        LEFT JOIN recipe_category rc ON r.id = rc.recipe_id
        LEFT JOIN categories c ON rc.category_id = c.id
        GROUP BY r.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meal Match - Find a Recipe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
    <main class="max-w-6xl mx-auto p-6">
        <h2 class="text-3xl font-bold text-gray-800 text-center">Find a Recipe</h2>
        <p class="text-lg text-gray-600 text-center mt-2">Discover new recipes tailored to your taste.</p>

        <div class="grid md:grid-cols-2 gap-6 mt-6">
            <?php while($recipe = $result->fetch_assoc()): ?>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <img src="<?= htmlspecialchars($recipe['image']) ?>" 
                     alt="<?= htmlspecialchars($recipe['title']) ?>" 
                     class="w-full h-48 object-cover mb-4 rounded-lg">
                <h3 class="text-xl font-semibold"><?= htmlspecialchars($recipe['title']) ?></h3>
                <div class="text-sm text-blue-500 mb-2">
                    <?= htmlspecialchars($recipe['categories']) ?>
                </div>
                <p class="text-gray-600"><?= htmlspecialchars($recipe['description']) ?></p>
                <a href="recipe.php?id=<?= $recipe['id'] ?>" 
                   class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    View Recipe
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>
</body>
</html>