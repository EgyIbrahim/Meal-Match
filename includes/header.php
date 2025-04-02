<!-- Replace the existing PHP session code with: -->
<?php
session_start();
require 'config.php';

// Fetch fresh categories and areas from database
$categoryQuery = "SELECT DISTINCT category FROM recipes WHERE category IS NOT NULL ORDER BY category ASC";
$categoryResult = $conn->query($categoryQuery);
$categories = $categoryResult->fetch_all(MYSQLI_ASSOC);

$areaQuery = "SELECT DISTINCT area FROM recipes WHERE area IS NOT NULL ORDER BY area ASC";
$areaResult = $conn->query($areaQuery);
$areas = $areaResult->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleElement(id) {
            document.getElementById(id).classList.toggle("hidden");
        }
    </script>
</head>
<body class="bg-[#F8EDEB]"> 
    
<header class="bg-[#FAF3E0] shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- Logo & Navigation -->
            <div class="flex items-center">
                <a href="index.php" class="text-2xl font-bold text-[#E63946]">Meal Match</a>
                <nav class="hidden md:flex space-x-6 ml-10">
                    <?php 
                        $pages = ["index" => "Home", "recipes" => "Recipes", "community" => "Community", "about" => "About"];
                        foreach ($pages as $file => $name) {
                            $active = basename($_SERVER['PHP_SELF'], ".php") === $file ? "text-blue-600 border-b-2 border-blue-600" : "text-gray-600 hover:text-blue-600";
                            echo "<a href='{$file}.php' class='{$active} text-sm font-medium px-1 pt-1'>{$name}</a>";
                        }
                    ?>
                </nav>
            </div>

            <!-- Search Bar with Filters -->
            <form action="search.php" method="GET" class="hidden md:flex items-center gap-2 relative">
                <!-- Search Input -->
                <div class="relative w-64">
                    <input type="text" name="query" id="recipe-search" placeholder="Search recipes..." 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="absolute right-3 top-2.5">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                    <!-- Dropdown for Recipe Suggestions -->
                    <div id="recipe-suggestions" class="absolute left-0 mt-1 bg-white border rounded-lg shadow-md hidden z-10 w-full">
                        <!-- Suggestions will be dynamically added here -->
                    </div>
                </div>

                <!-- Category Dropdown -->
<select name="category" class="px-2 py-2 border rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
    <option value="">Select Category</option>
    <?php foreach ($categories as $cat): ?>
        <option value="<?= htmlspecialchars($cat['category']) ?>">
            <?= htmlspecialchars($cat['category']) ?>
        </option>
    <?php endforeach; ?>
</select>

<!-- Area Dropdown -->
<select name="area" class="px-2 py-2 border rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
    <option value="">Select Area</option>
    <?php foreach ($areas as $area): ?>
        <option value="<?= htmlspecialchars($area['area']) ?>">
            <?= htmlspecialchars($area['area']) ?>
        </option>
    <?php endforeach; ?>
</select>
            </form>

            <!-- Account Menu -->
            <div class="relative">
                <?php if (isset($_SESSION['username'])): ?>
                    <button onclick="toggleElement('user-dropdown')" class="flex items-center text-sm bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-blue-500">
                        <span class="mr-2 text-gray-800"><?= htmlspecialchars($_SESSION['username']) ?></span>
                        <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                        <a href="settings.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Settings</a>
                        <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</a>
                    </div>
                <?php else: ?>
                    <div class="flex space-x-4">
                        <a href="login.php" class="text-gray-600 hover:text-blue-600 text-sm font-medium">Login</a>
                        <a href="signup.php" class="text-gray-600 hover:text-blue-600 text-sm font-medium">Sign Up</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <button onclick="toggleElement('mobile-menu')" class="md:hidden text-gray-600 hover:text-gray-800 focus:ring-2 focus:ring-blue-500">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <?php foreach ($pages as $file => $name): ?>
                <a href="<?= $file ?>.php" class="block px-3 py-2 text-gray-600 rounded-md text-base font-medium hover:text-blue-600"><?= $name ?></a>
            <?php endforeach; ?>
            <?php if (!isset($_SESSION['username'])): ?>
                <a href="login.php" class="block px-3 py-2 text-gray-600 rounded-md text-base font-medium hover:text-blue-600">Login</a>
                <a href="signup.php" class="block px-3 py-2 text-gray-600 rounded-md text-base font-medium hover:text-blue-600">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<script>
    const recipeSearch = document.getElementById('recipe-search');
    const recipeSuggestions = document.getElementById('recipe-suggestions');

    // Fetch recipe suggestions as the user types
    recipeSearch.addEventListener('input', async () => {
        const query = recipeSearch.value.trim();
        if (query.length < 2) {
            recipeSuggestions.classList.add('hidden');
            return;
        }

        const response = await fetch(`fetch_recipes.php?query=${encodeURIComponent(query)}`);
        const data = await response.json();

        recipeSuggestions.innerHTML = '';
        if (data.length > 0) {
            data.forEach(recipe => {
                const suggestion = document.createElement('div');
                suggestion.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer';
                suggestion.textContent = recipe.title;
                suggestion.addEventListener('click', () => {
                    recipeSearch.value = recipe.title;
                    recipeSuggestions.classList.add('hidden');
                });
                recipeSuggestions.appendChild(suggestion);
            });
            recipeSuggestions.classList.remove('hidden');
        } else {
            recipeSuggestions.classList.add('hidden');
        }
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', (event) => {
        if (!recipeSearch.contains(event.target) && !recipeSuggestions.contains(event.target)) {
            recipeSuggestions.classList.add('hidden');
        }
    });
</script>

</body>
</html>