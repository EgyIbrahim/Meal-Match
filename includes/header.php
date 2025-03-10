<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Toggle mobile menu
        function toggleMobileMenu() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        }

        // Toggle user dropdown
        function toggleUserDropdown() {
            document.getElementById('user-dropdown').classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.user-menu-button')) {
                const dropdowns = document.getElementsByClassName("user-dropdown");
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (!openDropdown.classList.contains('hidden')) {
                        openDropdown.classList.add('hidden');
                    }
                }
            }
        }
    </script>
</head>
<body style="background-image: url('https://images.pexels.com/photos/616360/pexels-photo-616360.jpeg?cs=srgb&dl=pexels-goumbik-616360.jpg&fm=jpg'); background-size: cover;">
<header class="bg-[#FAF3E0] shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo and main navigation -->
            <div class="flex items-center">
                <a href="index.php" class="text-2xl font-bold text-[#E63946]">Meal Match</a>
                <nav class="hidden md:flex space-x-8 ml-10">
                    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500' ?> hover:text-blue-600 inline-flex items-center px-1 pt-1 text-sm font-medium">Home</a>
                    <a href="recipes.php" class="<?= basename($_SERVER['PHP_SELF']) == 'recipes.php' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500' ?> hover:text-blue-600 inline-flex items-center px-1 pt-1 text-sm font-medium">Recipes</a>
                    <a href="about.php" class="<?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500' ?> hover:text-blue-600 inline-flex items-center px-1 pt-1 text-sm font-medium">About</a>
                </nav>
            </div>

            <!-- Search Bar -->
            <form action="search.php" method="GET" class="hidden md:flex relative">
                <input type="text" name="query" placeholder="Search by recipe or ingredients..." class="w-60 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="absolute right-3 top-2.5">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>

            <!-- Account Menu -->
            <div class="ml-4 relative">
                <?php if(isset($_SESSION['username'])): ?>
                    <div class="relative">
                        <button onclick="toggleUserDropdown()" class="user-menu-button flex items-center text-sm bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <span class="mr-2 text-gray-800"><?= htmlspecialchars($_SESSION['username']) ?></span>
                            <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </button>

                        <!-- Account dropdown -->
                        <div id="user-dropdown" class="user-dropdown hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5">
                            <a href="dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                            <a href="stats.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Stats</a>
                            <a href="settings.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                            <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex space-x-4">
                        <a href="login.php" class="<?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'text-blue-600' : 'text-gray-500' ?> hover:text-blue-600 inline-flex items-center px-1 pt-1 text-sm font-medium">Login</a>
                        <a href="signup.php" class="<?= basename($_SERVER['PHP_SELF']) == 'signup.php' ? 'text-blue-600' : 'text-gray-500' ?> hover:text-blue-600 inline-flex items-center px-1 pt-1 text-sm font-medium">Sign Up</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Mobile menu button -->
            <div class="-mr-2 flex md:hidden">
                <button onclick="toggleMobileMenu()" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="index.php" class="block px-3 py-2 text-gray-500 rounded-md text-base font-medium">Home</a>
            <a href="recipes.php" class="block px-3 py-2 text-gray-500 rounded-md text-base font-medium">Recipes</a>
            <a href="about.php" class="block px-3 py-2 text-gray-500 rounded-md text-base font-medium">About</a>
            <a href="login.php" class="block px-3 py-2 text-gray-500 rounded-md text-base font-medium">Login</a>
            <a href="signup.php" class="block px-3 py-2 text-gray-500 rounded-md text-base font-medium">Sign Up</a>
        </div>
    </div>
</header>
