<?php session_start(); 
// Fetch filter options from API and cache in session
if (!isset($_SESSION['categories'])) {
    $categories = json_decode(file_get_contents('https://www.themealdb.com/api/json/v1/1/list.php?c=list'), true);
    $_SESSION['categories'] = $categories['meals'];
}

if (!isset($_SESSION['areas'])) {
    $areas = json_decode(file_get_contents('https://www.themealdb.com/api/json/v1/1/list.php?a=list'), true);
    $_SESSION['areas'] = $areas['meals'];
}
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
            <form action="search.php" method="GET" class="hidden md:flex items-center gap-2">
                <!-- Search Input -->
                <div class="relative">
                    <input type="text" name="query" placeholder="Search recipes..." 
                           class="w-48 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="absolute right-3 top-2.5">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>

                <!-- Category Filter Dropdown -->
                <select name="category" class="px-2 py-2 border rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    <?php foreach ($_SESSION['categories'] as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['strCategory']) ?>">
                            <?= htmlspecialchars($cat['strCategory']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Area/Region Filter Dropdown -->
                <select name="area" class="px-2 py-2 border rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">All Regions</option>
                    <?php foreach ($_SESSION['areas'] as $area): ?>
                        <option value="<?= htmlspecialchars($area['strArea']) ?>">
                            <?= htmlspecialchars($area['strArea']) ?>
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
    document.addEventListener("click", function(event) {
        if (!event.target.closest(".relative")) {
            document.getElementById("user-dropdown")?.classList.add("hidden");
        }
    });
</script>

</body>
</html>