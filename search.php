<?php
session_start();
require 'config.php';
include('includes/header.php');

// Get all search parameters
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$area = isset($_GET['area']) ? trim($_GET['area']) : '';
$ingredients = isset($_GET['ingredients']) ? explode(',', trim($_GET['ingredients'])) : [];

// Initialize variables
$meals = [];
$search_type = '';

// Search by Ingredients
if (!empty($ingredients)) {
    $search_type = 'ingredients';
    $placeholders = implode(',', array_fill(0, count($ingredients), '?'));
    $sql = "
        SELECT r.*, 
               COUNT(i.id) AS matching_ingredients, 
               (SELECT COUNT(*) FROM recipe_ingredients WHERE recipe_id = r.id) - COUNT(i.id) AS missing_ingredients
        FROM recipes r
        JOIN recipe_ingredients ri ON r.id = ri.recipe_id
        JOIN ingredients i ON ri.ingredient_id = i.id
        WHERE i.name IN ($placeholders)
        GROUP BY r.id
        HAVING matching_ingredients > 0
        ORDER BY missing_ingredients ASC, matching_ingredients DESC
    ";
    $params = $ingredients;
    $types = str_repeat('s', count($ingredients));
} 
// Search by Filters (Category, Area, Query)
else {
    $search_type = 'filters';
    $sql = "SELECT DISTINCT r.* FROM recipes r 
            JOIN recipe_ingredients ri ON r.id = ri.recipe_id 
            JOIN ingredients i ON ri.ingredient_id = i.id 
            WHERE 1=1";
    $params = [];
    $types = '';

    if (!empty($query)) {
        $sql .= " AND r.title LIKE CONCAT('%', ?, '%')";
        $params[] = $query;
        $types .= 's';
    }

    if (!empty($category)) {
        $sql .= " AND r.category = ?";
        $params[] = $category;
        $types .= 's';
    }

    if (!empty($area)) {
        $sql .= " AND r.area = ?";
        $params[] = $area;
        $types .= 's';
    }
}

// Execute the query
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$meals = [];
while ($row = $result->fetch_assoc()) {
    $meals[] = $row;
}

// Process user ingredients (if searching by ingredients)
if ($search_type === 'ingredients') {
    $user_ingredients = array_map('trim', $ingredients);
    $user_ingredients_lower = array_map('strtolower', $user_ingredients);

    foreach ($meals as &$meal) {
        // Fetch all ingredients for the current recipe
        $recipe_id = $meal['id'];
        $ingredient_query = "
            SELECT i.name 
            FROM ingredients i
            JOIN recipe_ingredients ri ON i.id = ri.ingredient_id
            WHERE ri.recipe_id = ?
        ";
        $ingredient_stmt = $conn->prepare($ingredient_query);
        $ingredient_stmt->bind_param('i', $recipe_id);
        $ingredient_stmt->execute();
        $ingredient_result = $ingredient_stmt->get_result();

        $recipe_ingredients = [];
        while ($ingredient_row = $ingredient_result->fetch_assoc()) {
            $recipe_ingredients[] = $ingredient_row['name'];
        }

        // Calculate missing ingredients
        $missing_ingredients = [];
        foreach ($recipe_ingredients as $ingredient) {
            if (!in_array(strtolower($ingredient), $user_ingredients_lower)) {
                $missing_ingredients[] = $ingredient;
            }
        }

        // Add missing ingredients to the meal data
        $meal['missing_ingredients'] = $missing_ingredients;
    }
    unset($meal);
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
    <!-- Page Title -->
    <h1 class="text-4xl font-bold mb-6 text-center text-gray-800">
      <?php
      if ($search_type === 'ingredients') {
          echo 'Recipes with Ingredients: "' . htmlspecialchars(implode(', ', $ingredients)) . '"';
      } elseif ($search_type === 'filters') {
          if (!empty($query)) {
              echo 'Search Results for "' . htmlspecialchars($query) . '"';
          } elseif (!empty($category)) {
              echo 'Meals in Category: "' . htmlspecialchars($category) . '"';
          } elseif (!empty($area)) {
              echo 'Meals from Region: "' . htmlspecialchars($area) . '"';
          } else {
              echo 'Search Results';
          }
      }
      ?>
    </h1>

    <!-- Search Results -->
    <?php if ($meals): ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($meals as $meal): ?>
          <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col">
            <!-- Meal Image -->
            <img src="<?php echo htmlspecialchars($meal['image']); ?>" 
                 alt="<?php echo htmlspecialchars($meal['title']); ?>" 
                 class="w-full h-48 object-cover">
            
            <!-- Meal Details -->
            <div class="p-4 flex flex-col flex-grow">
              <h2 class="text-xl font-semibold mb-2 text-gray-800">
                <?php echo htmlspecialchars($meal['title']); ?>
              </h2>
              <p class="text-sm text-gray-500 mb-4">
                <!-- Display Region, Cuisine, and Category -->
                <span class="block"><strong>Region:</strong> <?php echo htmlspecialchars($meal['area'] ?? 'Unknown'); ?></span>
                <span class="block"><strong>Category:</strong> <?php echo htmlspecialchars($meal['category'] ?? 'Unknown'); ?></span>
              </p>

              <!-- Missing Ingredients -->
              <?php if ($search_type === 'ingredients' && !empty($meal['missing_ingredients'])): ?>
                  <div class="text-sm text-red-600 mb-2">
                      <strong>Missing Ingredients:</strong> <?php echo htmlspecialchars(implode(', ', $meal['missing_ingredients'])); ?>
                  </div>
              <?php endif; ?>

              <a href="recipe.php?id=<?php echo htmlspecialchars($meal['id']); ?>" 
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