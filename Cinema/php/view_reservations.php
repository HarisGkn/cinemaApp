<?php
session_start();
require_once "config.php";

// Check if user is not logged in, then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.html');
    exit();
}

// Check if user has admin role, then allow access
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Delete reservation if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reservation'])) {
    $reservationid = $_POST['reservationid'];

    $deleteQuery = "DELETE FROM reservations WHERE reservationid = ?";
    $stmt = mysqli_prepare($link, $deleteQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $reservationid);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: view_reservations.php');
            exit();
        }

        mysqli_stmt_close($stmt);
    }
}

// Fetch reservations from the database for admin
$query = "SELECT r.reservationid, p.name AS product_name, r.reservationdate, r.status
          FROM reservations r
          INNER JOIN products p ON r.productid = p.productid";

$result = mysqli_query($link, $query);

$reservations = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reservations[] = $row;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Reservations</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/script.js"></script>
</head>
<body>
    <header>
        <h1>
            <a href="index.php">CinemaApp</a>
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
        <h1>View Reservations</h1>
        <table>
            <tr>
                <th>Reservation ID</th>
                <th>Product Name</th>
                <th>Reservation Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($reservations as $reservation) { ?>
                <tr>
                    <td><?php echo $reservation['reservationid']; ?></td>
                    <td><?php echo $reservation['product_name']; ?></td>
                    <td><?php echo $reservation['reservationdate']; ?></td>
                    <td><?php echo $reservation['status']; ?></td>
                    <td>
                        <form class="action-form" method="post">
                            <input type="hidden" name="reservationid" value="<?php echo $reservation['reservationid']; ?>">
                            <button type="submit" name="delete_reservation">Delete</button>
                        </form>
                        <form class="action-form" action="update_reservation.php" method="get">
                            <input type="hidden" name="reservationid" value="<?php echo $reservation['reservationid']; ?>">
                            <button type="submit">Edit</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <footer>
        <p>&copy; CinemaApp 2023. All rights reserved.</p>
    </footer>
</body>
</html>
