<?php
require 'config.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (!empty($query)) {
    $stmt = $conn->prepare("SELECT DISTINCT name FROM ingredients WHERE name LIKE CONCAT('%', ?, '%') LIMIT 10");
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    $ingredients = [];
    while ($row = $result->fetch_assoc()) {
        $ingredients[] = $row;
    }

    echo json_encode($ingredients);
} else {
    echo json_encode([]);
}
?>