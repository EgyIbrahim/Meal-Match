<?php
include('config.php');
include('includes/header.php');

// Fetch a random recipe from the database
$sql = "SELECT * FROM recipes ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);
$randomMeal = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meal Match - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #ingredient-suggestions {
            max-height: 200px;
            overflow-y: auto;
            z-index: 10;
            position: absolute;
            width: 100%;
        }
        #ingredient-suggestions div {
            cursor: pointer;
            padding: 8px;
        }
        #ingredient-suggestions div:hover {
            background-color: #f3f4f6; /* Tailwind utility for light gray background */
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">
    <section class="text-center py-16 bg-gradient-to-r from-yellow-400 via-red-400 to-pink-500 text-white">
        <h1 class="text-5xl font-extrabold">Welcome to Meal Match!</h1>
        <p class="text-lg mt-3">Your ultimate destination for delicious recipes and meal inspiration.</p>
        <a href="recipes.php" class="mt-6 inline-block bg-white text-red-500 font-semibold px-6 py-3 rounded-lg hover:bg-gray-100 transition">
            Explore Recipes
        </a>
    </section>

    <section class="text-center py-8">
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row gap-6">
            <!-- Ingredient Search -->
            <div class="flex-grow relative">
                <label for="ingredient-search" class="block text-gray-700 font-semibold mb-2">Enter Ingredients You Have:</label>
                <input type="text" id="ingredient-search" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Enter an ingredient...">
                <div id="ingredient-suggestions" class="absolute left-0 mt-1 bg-white border rounded-lg shadow-md hidden">
                    <!-- Suggestions will be dynamically added here -->
                </div>
            </div>

            <!-- Selected Ingredients (Cart) -->
            <div class="w-full md:w-1/3 bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-4">Your Ingredients</h3>
                <ul id="ingredient-cart" class="list-disc pl-5 space-y-2">
                    <!-- Selected ingredients will appear here -->
                </ul>
                <button id="search-recipes" class="mt-4 w-full bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                    Find Recipes
                </button>
            </div>
        </div>
    </section>

    <script>
        const ingredientSearch = document.getElementById('ingredient-search');
        const ingredientSuggestions = document.getElementById('ingredient-suggestions');
        const ingredientCart = document.getElementById('ingredient-cart');
        const selectedIngredients = [];

        // Show dropdown when the search bar is clicked
        ingredientSearch.addEventListener('focus', async () => {
            const query = ingredientSearch.value.trim();
            if (query.length < 2) {
                ingredientSuggestions.classList.add('hidden');
                return;
            }

            const response = await fetch(`fetch_ingredients.php?query=${query}`);
            const data = await response.json();

            ingredientSuggestions.innerHTML = '';
            if (data.length > 0) {
                data.forEach(ingredient => {
                    const suggestion = document.createElement('div');
                    suggestion.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer';
                    suggestion.textContent = ingredient.name;
                    suggestion.addEventListener('click', () => {
                        addIngredient(ingredient.name);
                    });
                    ingredientSuggestions.appendChild(suggestion);
                });
                ingredientSuggestions.classList.remove('hidden');
            } else {
                ingredientSuggestions.classList.add('hidden');
            }
        });

        // Fetch ingredient suggestions from the backend
        ingredientSearch.addEventListener('input', async () => {
            const query = ingredientSearch.value.trim();
            if (query.length < 2) {
                ingredientSuggestions.classList.add('hidden');
                return;
            }

            const response = await fetch(`fetch_ingredients.php?query=${query}`);
            const data = await response.json();

            ingredientSuggestions.innerHTML = '';
            if (data.length > 0) {
                data.forEach(ingredient => {
                    const suggestion = document.createElement('div');
                    suggestion.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer';
                    suggestion.textContent = ingredient.name;
                    suggestion.addEventListener('click', () => {
                        addIngredient(ingredient.name);
                    });
                    ingredientSuggestions.appendChild(suggestion);
                });
                ingredientSuggestions.classList.remove('hidden');
            } else {
                ingredientSuggestions.classList.add('hidden');
            }
        });

        // Add ingredient to the cart
        function addIngredient(ingredient) {
            if (!selectedIngredients.includes(ingredient)) {
                selectedIngredients.push(ingredient);
                const listItem = document.createElement('li');
                listItem.textContent = ingredient;
                ingredientCart.appendChild(listItem);
            }
            ingredientSearch.value = '';
            ingredientSuggestions.classList.add('hidden');
        }

        // Hide dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!ingredientSearch.contains(event.target) && !ingredientSuggestions.contains(event.target)) {
                ingredientSuggestions.classList.add('hidden');
            }
        });

        // Suggest recipes based on selected ingredients
        document.getElementById('search-recipes').addEventListener('click', () => {
            if (selectedIngredients.length === 0) {
                alert('Please add at least one ingredient.');
                return;
            }

            const ingredients = selectedIngredients.join(',');
            window.location.href = `search.php?ingredients=${encodeURIComponent(ingredients)}`;
        });
    </script>

    <!-- Random Recipe -->
    <?php if ($randomMeal): ?>
    <section class="max-w-6xl mx-auto py-12">
        <h2 class="text-3xl font-bold text-center mb-6">Recipe of the Day</h2>
        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col md:flex-row">
            <img src="<?= htmlspecialchars($randomMeal['image']) ?>" alt="<?= htmlspecialchars($randomMeal['title']) ?>" class="w-full md:w-1/3 rounded-lg">
            <div class="p-6">
                <h3 class="text-2xl font-bold"><?= htmlspecialchars($randomMeal['title']) ?></h3>
                <p class="text-gray-600"><?= htmlspecialchars($randomMeal['category']) ?> | <?= htmlspecialchars($randomMeal['area']) ?></p>
                <a href="recipe.php?id=<?= htmlspecialchars($randomMeal['id']) ?>" class="mt-4 inline-block bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                    View Recipe
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php include('includes/footer.php'); ?>
</body>
</html>
