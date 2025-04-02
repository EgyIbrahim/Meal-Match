<?php
include('includes/header.php');
require 'config.php'; // Include database connection

// Fetch all recipes from the database
$sql = "SELECT * FROM recipes";
$result = $conn->query($sql);

$meals = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $meals[] = $row;
    }
} else {
    // Fallback to API if no recipes are found in the database
    $api_url = "https://www.themealdb.com/api/json/v1/1/filter.php?i=chicken_breast";
    $response = file_get_contents($api_url);
    $meals = json_decode($response, true)['meals'];
}
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

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            <?php if (!empty($meals)): ?>
                <?php foreach ($meals as $meal): ?>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <img src="<?= htmlspecialchars($meal['image'] ?? $meal['strMealThumb']) ?>" 
                             alt="<?= htmlspecialchars($meal['title'] ?? $meal['strMeal']) ?>" 
                             class="w-full h-48 object-cover mb-4 rounded-lg">
                        <h3 class="text-xl font-semibold"><?= htmlspecialchars($meal['title'] ?? $meal['strMeal']) ?></h3>
                        <a href="recipe.php?id=<?= htmlspecialchars($meal['id'] ?? $meal['idMeal']) ?>" 
                           class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            View Recipe
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-red-500">No recipes found.</p>
            <?php endif; ?>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>
</body>
</html>
