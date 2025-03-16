<?php
session_start();
require 'config.php';
include('includes/header.php');

// Get all search parameters
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$area = isset($_GET['area']) ? trim($_GET['area']) : '';
$ingredients = isset($_GET['ingredients']) ? trim($_GET['ingredients']) : '';

// Initialize variables
$meals = [];
$search_type = '';

// Function to get recipe ingredients
function getRecipeIngredients($meal_id) {
    $details_url = "https://www.themealdb.com/api/json/v1/1/lookup.php?i=" . $meal_id;
    $details_response = @file_get_contents($details_url);
    $details_data = json_decode($details_response, true);
    
    if (!$details_data || !isset($details_data['meals'][0])) return [];
    
    $ingredients = [];
    for ($i = 1; $i <= 20; $i++) {
        $ingredient = strtolower(trim($details_data['meals'][0]['strIngredient' . $i]));
        if (!empty($ingredient)) {
            $ingredients[] = $ingredient;
        }
    }
    return $ingredients;
}

// Handle ingredient search
if (!empty($ingredients)) {
    $search_type = 'ingredients';
    $user_ingredients = array_map('trim', explode(',', strtolower($ingredients)));
    $user_ingredients = array_unique(array_filter($user_ingredients));
    
    if (!empty($user_ingredients)) {
        // Use first ingredient for initial filtering
        $first_ingredient = urlencode($user_ingredients[0]);
        $api_url = "https://www.themealdb.com/api/json/v1/1/filter.php?i=" . $first_ingredient;
        $response = @file_get_contents($api_url);
        $data = json_decode($response, true);
        $meals = isset($data['meals']) ? $data['meals'] : [];

        // Get details and check ingredients
        $detailed_meals = [];
        foreach ($meals as $meal) {
            $meal_id = $meal['idMeal'];
            $recipe_ingredients = getRecipeIngredients($meal_id);
            
            // Count matching ingredients
            $match_count = count(array_intersect($user_ingredients, $recipe_ingredients));
            $missing = count($user_ingredients) - $match_count;
            
            if ($missing <= 1) {
                $details_url = "https://www.themealdb.com/api/json/v1/1/lookup.php?i=" . $meal_id;
                $details_response = @file_get_contents($details_url);
                $details_data = json_decode($details_response, true);
                if ($details_data && isset($details_data['meals'][0])) {
                    $meal_details = $details_data['meals'][0];
                    $meal_details['match_count'] = $match_count;
                    $meal_details['missing'] = $missing;
                    $detailed_meals[] = $meal_details;
                }
            }
        }
        
        // Sort by best match
        usort($detailed_meals, function($a, $b) {
            return $b['match_count'] - $a['match_count'];
        });
        
        $meals = $detailed_meals;
    }
}
// Handle other search types
else {
    $api_url = "https://www.themealdb.com/api/json/v1/1/";
    
    if (!empty($query)) {
        $search_type = 'query';
        $api_url .= "search.php?s=" . urlencode($query);
    } elseif (!empty($category)) {
        $search_type = 'category';
        $api_url .= "filter.php?c=" . urlencode($category);
    } elseif (!empty($area)) {
        $search_type = 'area';
        $api_url .= "filter.php?a=" . urlencode($area);
    }

    if (!empty($api_url)) {
        $response = @file_get_contents($api_url);
        $data = json_decode($response, true);
        $meals = isset($data['meals']) ? $data['meals'] : null;

        // Get full details for filtered meals
        if ($meals && in_array($search_type, ['category', 'area'])) {
            $detailed_meals = [];
            foreach ($meals as $meal) {
                $details_url = "https://www.themealdb.com/api/json/v1/1/lookup.php?i=" . $meal['idMeal'];
                $details_response = @file_get_contents($details_url);
                $details_data = json_decode($details_response, true);
                if ($details_data && isset($details_data['meals'][0])) {
                    $detailed_meals[] = $details_data['meals'][0];
                }
            }
            $meals = $detailed_meals;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Search Results</title>
</head>
<body class="bg-gray-100">
  <div class="container mx-auto px-4 py-8 max-w-7xl">
    <h1 class="text-3xl font-bold mb-6 text-center">
      <?php
      if ($search_type === 'ingredients') {
          echo 'Recipes using: ' . htmlspecialchars($ingredients);
      } elseif (!empty($query)) {
          echo 'Search Results for "' . htmlspecialchars($query) . '"';
      } elseif (!empty($category)) {
          echo 'Meals in Category: "' . htmlspecialchars($category) . '"';
      } elseif (!empty($area)) {
          echo 'Meals from Region: "' . htmlspecialchars($area) . '"';
      } else {
          echo 'Search Results';
      }
      ?>
    </h1>
    
    <?php if (empty($query) && empty($category) && empty($area) && empty($ingredients)): ?>
      <p class="text-gray-600 text-center">Please enter a search term or select a filter.</p>
    
    <?php elseif ($meals): ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4">
        <?php foreach ($meals as $meal): ?>
          <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 h-full flex flex-col">
            <img src="<?php echo htmlspecialchars($meal['strMealThumb']); ?>" 
                 alt="<?php echo htmlspecialchars($meal['strMeal']); ?>" 
                 class="w-full h-48 object-cover">
            <div class="p-4 flex flex-col flex-grow">
              <h2 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($meal['strMeal']); ?></h2>
              <p class="text-sm text-gray-500 mb-4">
                <?php echo htmlspecialchars($meal['strArea'] ?? 'N/A'); ?> | <?php echo htmlspecialchars($meal['strCategory'] ?? 'N/A'); ?>
                <?php if (isset($meal['missing'])): ?>
                  <br><span class="text-xs text-red-500">Missing <?php echo $meal['missing']; ?> ingredient<?php echo $meal['missing'] > 1 ? 's' : ''; ?></span>
                <?php endif; ?>
              </p>
              <a href="recipe.php?id=<?php echo htmlspecialchars($meal['idMeal']); ?>" 
                 class="mt-auto inline-block bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors duration-200 text-center">
                View Recipe
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    
    <?php else: ?>
      <p class="text-gray-600 text-center">No meals found. Try a different search or filter.</p>
    <?php endif; ?>
  </div>
  
  <?php include('includes/footer.php'); ?>
</body>
</html>