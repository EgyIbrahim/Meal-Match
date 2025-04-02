<footer class="bg-white shadow-md mt-auto">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About Section -->
            <div>
                <h3 class="text-lg font-semibold mb-4">About Meal Match</h3>
                <p class="text-gray-600">Discover, share, and enjoy delicious recipes from around the world.</p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="index.php" class="text-gray-600 hover:text-blue-500">Home</a></li>
                    <li><a href="recipes.php" class="text-gray-600 hover:text-blue-500">Recipes</a></li>
                    <li><a href="about.php" class="text-gray-600 hover:text-blue-500">About Us</a></li>
                </ul>
            </div>

            <!-- Newsletter Signup -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Subscribe for Weekly Recipes</h3>
                <form action="subscribe.php" method="POST" class="mt-4">
                    <input type="email" name="email" placeholder="Enter your email" class="p-3 rounded-lg w-full border border-gray-300">
                    <button type="submit" class="mt-3 w-full bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                        Subscribe
                    </button>
                </form>
            </div>

            <!-- Legal -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Legal</h3>
                <ul class="space-y-2">
                    <li><a href="privacy.php" class="text-gray-600 hover:text-blue-500">Privacy Policy</a></li>
                    <li><a href="terms.php" class="text-gray-600 hover:text-blue-500">Terms of Service</a></li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-gray-100 mt-8 pt-8 text-center">
            <p class="text-gray-600">&copy; <?= date('Y') ?> Meal Match. All rights reserved.</p>
        </div>
    </div>
</footer>
