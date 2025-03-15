<?php
include('config.php');
include('includes/header.php');

// Fetch random recipes from the API
$apiUrl = "https://www.themealdb.com/api/json/v1/1/random.php";
$response = @file_get_contents($apiUrl);
$randomMeal = json_decode($response, true)['meals'][0] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meal Match - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
   
    <!-- Hero Section -->
    <section class="text-center py-16 bg-gradient-to-r from-yellow-400 via-red-400 to-pink-500 text-white">
        <h1 class="text-5xl font-extrabold">Welcome to Meal Match!</h1>
        <p class="text-lg mt-3">Your ultimate destination for delicious recipes and meal inspiration.</p>
        <a href="recipes.php" class="mt-6 inline-block bg-white text-red-500 font-semibold px-6 py-3 rounded-lg hover:bg-gray-100 transition">
            Explore Recipes
        </a>
    </section>

    <!-- Search Bar -->
    <section class="text-center py-8">
        <form action="search.php" method="GET" class="max-w-md mx-auto">
            <input type="text" name="ingredient" class="w-full p-3 border rounded-lg" placeholder="Enter an ingredient...">
            <button type="submit" class="mt-3 bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">
                Search Recipes
            </button>
        </form>
    </section>

    <!-- Featured Categories -->
    <section class="max-w-6xl mx-auto py-12">
        <h2 class="text-3xl font-bold text-center mb-6">Browse by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <a href="category.php?name=Breakfast" class="bg-yellow-200 p-6 rounded-lg hover:bg-yellow-300">Breakfast</a>
            <a href="category.php?name=Lunch" class="bg-green-200 p-6 rounded-lg hover:bg-green-300">Lunch</a>
            <a href="category.php?name=Dinner" class="bg-blue-200 p-6 rounded-lg hover:bg-blue-300">Dinner</a>
            <a href="category.php?name=Dessert" class="bg-pink-200 p-6 rounded-lg hover:bg-pink-300">Dessert</a>
        </div>
    </section>

    <!-- Random Recipe -->
    <?php if ($randomMeal): ?>
    <section class="max-w-6xl mx-auto py-12">
        <h2 class="text-3xl font-bold text-center mb-6">Recipe of the Day</h2>
        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col md:flex-row">
            <img src="<?= htmlspecialchars($randomMeal['strMealThumb']) ?>" alt="<?= htmlspecialchars($randomMeal['strMeal']) ?>" class="w-full md:w-1/3 rounded-lg">
            <div class="p-6">
                <h3 class="text-2xl font-bold"><?= htmlspecialchars($randomMeal['strMeal']) ?></h3>
                <p class="text-gray-600"><?= htmlspecialchars($randomMeal['strCategory']) ?> | <?= htmlspecialchars($randomMeal['strArea']) ?></p>
                <a href="recipe.php?id=<?= $randomMeal['idMeal'] ?>" class="mt-4 inline-block bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                    View Recipe
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php include('includes/footer.php'); ?>
</body>
</html>
