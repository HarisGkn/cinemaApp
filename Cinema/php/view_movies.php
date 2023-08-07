<?php
session_start();
require_once "config.php";


// Fetch movies from the Java service endpoint
$endpointUrl = "http://localhost:8080/CinemaService/products"; // Adjust the URL accordingly
$response = file_get_contents($endpointUrl);
$movies = json_decode($response, true);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Movies</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>CinemaApp</h1>
        <nav>
        <a href="index.php">Home</a>
        <a href="view_movies.php">list Movies</a>
            <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                    if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
                        echo '<a href="my_info.php">My Info</a>';
                    } elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                        echo '<a href="view_reservations.php">View Reservations</a>';
                        echo '<a href="create_product.php">Add Movies</a>';
                        echo '<a href="manage_products.php">Manage Movies</a>';
                        echo '<a href="manage_users.php">Manage Users</a>';
                        echo '<a href="registration_requests.php">Manage Registration Requests</a>';
                    }
                    
                    echo '<a href="logout.php">Logout</a>';
                } else {
                    echo '<a href="login.php">Login</a>';
                    echo '<a href="register.php">Register</a>';
                }
            ?>
        </nav>
    </header>
    <div class="container">
        <h1>Manage Movies</h1>
        <table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Type</th>
            </tr>
            <?php foreach ($movies as $movie) { ?>
                <tr>
                    <td><?php echo $movie['name']; ?></td>
                    <td><?php echo $movie['description']; ?></td>
                    <td><?php echo $movie['price']; ?></td>
                    <td><?php echo $movie['type']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <footer>
        <p>&copy; CinemaApp 2023. All rights reserved.</p>
    </footer>
</body>
</html>
