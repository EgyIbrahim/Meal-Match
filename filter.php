<?php
session_start();
require 'config.php'; // Include your config file if needed
include('includes/header.php'); // Include your header

// Get the filter parameters from the GET request
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$area = isset($_GET['area']) ? trim($_GET['area']) : '';

// Use the test API key "1" for development
$api_url = "https://www.themealdb.com/api/json/v1/1/";

// Arrays to store meals from each filter
$meals_by_category = [];
$meals_by_area = [];

// Fetch meals by category if provided
if (!empty($category)) {
    $category_url = $api_url . "filter.php?c=" . urlencode($category);
    $category_response = @file_get_contents($category_url);
    $category_data = json_decode($category_response, true);
    $meals_by_category = isset($category_data['meals']) ? $category_data['meals'] : [];
}

// Fetch meals by area if provided
if (!empty($area)) {
    $area_url = $api_url . "filter.php?a=" . urlencode($area);
    $area_response = @file_get_contents($area_url);
    $area_data = json_decode($area_response, true);
    $meals_by_area = isset($area_data['meals']) ? $area_data['meals'] : [];
}

// Find common meals between the two filters
$common_meals = [];
if (!empty($meals_by_category) && !empty($meals_by_area)) {
    // Create an array of meal IDs from each filter
    $category_meal_ids = array_column($meals_by_category, 'idMeal');
    $area_meal_ids = array_column($meals_by_area, 'idMeal');

    // Find the intersection of meal IDs
    $common_meal_ids = array_intersect($category_meal_ids, $area_meal_ids);

    // Fetch full details for common meals
    foreach ($common_meal_ids as $meal_id) {
        $details_url = $api_url . "lookup.php?i=" . $meal_id;
        $details_response = @file_get_contents($details_url);
        $details_data = json_decode($details_response, true);
        if ($details_data && isset($details_data['meals'][0])) {
            $common_meals[] = $details_data['meals'][0];
        }
    }
} elseif (!empty($meals_by_category)) {
    // If only category is provided, use those meals
    foreach ($meals_by_category as $meal) {
        $details_url = $api_url . "lookup.php?i=" . $meal['idMeal'];
        $details_response = @file_get_contents($details_url);
        $details_data = json_decode($details_response, true);
        if ($details_data && isset($details_data['meals'][0])) {
            $common_meals[] = $details_data['meals'][0];
        }
    }
} elseif (!empty($meals_by_area)) {
    // If only area is provided, use those meals
    foreach ($meals_by_area as $meal) {
        $details_url = $api_url . "lookup.php?i=" . $meal['idMeal'];
        $details_response = @file_get_contents($details_url);
        $details_data = json_decode($details_response, true);
        if ($details_data && isset($details_data['meals'][0])) {
            $common_meals[] = $details_data['meals'][0];
        }
    }
}

// Redirect to search.php with the results
$_SESSION['filtered_meals'] = $common_meals;
header('Location: search.php');
exit;
?>