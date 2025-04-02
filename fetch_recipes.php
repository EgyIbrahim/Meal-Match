<?php
require 'config.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (!empty($query)) {
    $stmt = $conn->prepare("SELECT id, title FROM recipes WHERE title LIKE CONCAT('%', ?, '%') LIMIT 10");
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    $recipes = [];
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }

    echo json_encode($recipes);
} else {
    echo json_encode([]);
}
?>