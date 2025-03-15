<?php
session_start();

// Get the search query from the GET parameter
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Use the test API key "1" for development (the base URL already uses "1")
$api_url = "https://www.themealdb.com/api/json/v1/1/search.php?s=" . urlencode($query);

// Fetch data from the API and decode the JSON response
$response = @file_get_contents($api_url);
$data = json_decode($response, true);

// Check if meals were returned
$meals = isset($data['meals']) ? $data['meals'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Search Results for "<?php echo htmlspecialchars($query); ?>"</title>
</head>
<body class="bg-gray-100">
  <!-- You can include your header/navigation here if desired -->
  <div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Search Results for "<?php echo htmlspecialchars($query); ?>"</h1>
    
    <?php if ($query === ''): ?>
      <p class="text-gray-600">Please enter a search term.</p>
    
    <?php elseif ($meals): ?>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ($meals as $meal): ?>
          <div class="bg-white rounded shadow p-4">
            <img src="<?php echo htmlspecialchars($meal['strMealThumb']); ?>" alt="<?php echo htmlspecialchars($meal['strMeal']); ?>" class="w-full h-40 object-cover rounded">
            <h2 class="text-xl font-semibold mt-2"><?php echo htmlspecialchars($meal['strMeal']); ?></h2>
            <p class="text-sm text-gray-500">
              <?php echo htmlspecialchars($meal['strArea']); ?> | <?php echo htmlspecialchars($meal['strCategory']); ?>
            </p>
            <a href="recipe.php?id=<?php echo htmlspecialchars($meal['idMeal']); ?>" class="mt-2 inline-block text-blue-500 hover:underline">
              View Recipe
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    
    <?php else: ?>
      <p class="text-gray-600">No meals found. Try a different search.</p>
    <?php endif; ?>
  </div>
</body>
</html>
