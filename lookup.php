<?php
session_start();
require 'config.php'; // Include your config file if needed

// Get the meal ID from the GET request
$meal_id = isset($_GET['id']) ? trim($_GET['id']) : '';

// Use the test API key "1" for development
$api_url = "https://www.themealdb.com/api/json/v1/1/lookup.php?i=" . urlencode($meal_id);

// Fetch data from the API
$response = @file_get_contents($api_url);
$data = json_decode($response, true);
$meal = isset($data['meals'][0]) ? $data['meals'][0] : null;

// Return the meal details as JSON
header('Content-Type: application/json');
echo json_encode($meal);
exit;
?>