<?php
session_start();

// Validate the recipe ID parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: recipes.php");
    exit();
}

$recipeId = (int)$_GET['id'];

// Build the API URL to look up full meal details by ID
$apiUrl = "https://www.themealdb.com/api/json/v1/1/lookup.php?i=" . $recipeId;

// Fetch the API response
$response = @file_get_contents($apiUrl);
if ($response === FALSE) {
    header("Location: recipes.php");
    exit();
}

$data = json_decode($response, true);

// Verify the API returned a valid meal
if (!isset($data['meals']) || count($data['meals']) === 0) {
    header("Location: recipes.php");
    exit();
}

// Get the meal details
$meal = $data['meals'][0];

// Set the page title dynamically using the meal name
$pageTitle = $meal['strMeal'] . " - Meal Match";

// Include the header
include('includes/header.php');
?>
  
<main class="max-w-4xl mx-auto p-6">
    <!-- Recipe Image -->
    <img src="<?= htmlspecialchars($meal['strMealThumb']) ?>" 
         alt="<?= htmlspecialchars($meal['strMeal']) ?>" 
         class="w-full h-64 object-cover rounded-lg mb-6">

    <!-- Recipe Title -->
    <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($meal['strMeal']) ?></h1>

    <!-- Recipe Metadata -->
    <div class="mb-6 text-gray-600">
        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded">
            <?= htmlspecialchars($meal['strCategory']) ?> | <?= htmlspecialchars($meal['strArea']) ?>
        </span>
    </div>

    <!-- Ingredients -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-2xl font-semibold mb-4">Ingredients</h2>
        <ul class="list-disc ml-6">
            <?php 
            // Loop through possible ingredient and measure fields (up to 20)
            for ($i = 1; $i <= 20; $i++) {
                $ingredient = $meal['strIngredient' . $i];
                $measure = $meal['strMeasure' . $i];
                if (!empty($ingredient) && trim($ingredient) !== '') {
                    echo "<li>" . htmlspecialchars($ingredient) . " - " . htmlspecialchars($measure) . "</li>";
                }
            }
            ?>
        </ul>
    </div>

    <!-- Instructions -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-4">Instructions</h2>
        <div class="whitespace-pre-wrap"><?= nl2br(htmlspecialchars($meal['strInstructions'])) ?></div>
    </div>
</main>

<?php include('includes/footer.php'); ?>
