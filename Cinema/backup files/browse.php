<?php
require_once 'config.php'; // Include the database connection

$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

$products = array();
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

// Adding movie names manually
$movieNames = [
    "Lawrence of Arabia",
    "Rambo - First Blood",
    "Beasts of No Nation",
    "The Last Samurai",
    "2 Fast 2 Furious",
    "Avatar"
];

// Combine movie names with products fetched from the database
foreach ($movieNames as $index => $movieName) {
    $products[$index]['name'] = $movieName;
}

echo json_encode($products);
?>
