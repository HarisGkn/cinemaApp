<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}
date_default_timezone_set('Europe/Athens'); //fix issue of date being shown wrong in the front-end due to timezone issues

$userid = $_SESSION['userid'];

// Java service endpoint URL
$endpointUrl = "http://localhost:8080/CinemaService/reservations/user/$userid";

// Send the HTTP GET request
$reservationsJson = file_get_contents($endpointUrl);

$reservations = [];
if ($reservationsJson) {
    $reservations = json_decode($reservationsJson, true);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>My Reservations</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/script.js"></script>
</head>
<body>
    <header>
        <h1>
            <a name="logo" href="index.php">CinemaApp</a>
        </h1>
        <!-- Navigation links based on user role and login status -->
        <nav>
            <a href="index.php">Home</a>
            <a href="view_movies.php">list Movies</a>
            <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                    if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
                        echo '<a href="create_reservation.php">Create Reservation</a>';
                        echo '<a href="user_view_reservations.php">My Reservations</a>';
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
        <h1>My Reservations</h1>
        <table>
            <tr>
                <th>Reservation ID</th>
                <th>Product ID</th>
                <th>Reservation Date</th>
                <th>Status</th>
            </tr>
            <?php foreach ($reservations as $reservation) { ?>
                <tr>
                    <td><?php echo $reservation['reservationid']; ?></td>
                    <td><?php echo $reservation['productid']; ?></td>
                    <td><?php echo date('Y-m-d', $reservation['reservationdate'] / 1000); ?></td>
                    <td><?php echo $reservation['status']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <footer>
        <p>&copy; CinemaApp 2023. All rights reserved.</p>
    </footer>
</body>
</html>
