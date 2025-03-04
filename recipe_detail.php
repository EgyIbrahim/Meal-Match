<?php
include('config.php');
include('includes/header.php');

if (!isset($_GET['id'])) {
    header("Location: recipes.php");
    exit;
}

$recipeId = $conn->real_escape_string($_GET['id']);
$sql = "SELECT r.*, u.username 
        FROM recipes r
        JOIN users u ON r.user_id = u.id
        WHERE r.id = $recipeId";
$recipe = $conn->query($sql)->fetch_assoc();

if (!$recipe) {
    header("Location: recipes.php");
    exit;
}
?>

<main class="max-w-4xl mx-auto p-6">
    <img src="<?= htmlspecialchars($recipe['image']) ?>" 
         alt="<?= htmlspecialchars($recipe['title']) ?>" 
         class="w-full h-64 object-cover rounded-lg mb-6">
    
    <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($recipe['title']) ?></h1>
    <div class="mb-6 text-gray-500">
        Posted by <?= htmlspecialchars($recipe['username']) ?> on 
        <?= date('F j, Y', strtotime($recipe['created_at'])) ?>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-gray-50 p-6 rounded-lg">
            <h2 class="text-2xl font-semibold mb-4">Ingredients</h2>
            <pre class="whitespace-pre-wrap font-sans"><?= htmlspecialchars($recipe['ingredients']) ?></pre>
        </div>
        
        <div class="bg-gray-50 p-6 rounded-lg">
            <h2 class="text-2xl font-semibold mb-4">Instructions</h2>
            <pre class="whitespace-pre-wrap font-sans"><?= htmlspecialchars($recipe['steps']) ?></pre>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>