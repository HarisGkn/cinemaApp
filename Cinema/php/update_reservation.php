<?php
session_start();
require_once "config.php";

// Check if user is not logged in, if not redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.html');
    exit();
}

// Check if user has admin role, then allow access
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservationid = $_POST['reservationid'];
    $status = $_POST['status'];

    // Validate status value
    $validStatusValues = array("Confirmed", "Cancelled", "Pending");
    if (!in_array($status, $validStatusValues)) {
        $error_message = 'Invalid status value.';
    } else {
        $updateQuery = "UPDATE reservations 
                        SET status = ?
                        WHERE reservationid = ?";
        
        $stmt = mysqli_prepare($link, $updateQuery);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $status, $reservationid);

            if (mysqli_stmt_execute($stmt)) {
                $success_message = 'Reservation updated successfully.';
            } else {
                $error_message = 'Failed to update reservation. Please try again.';
            }

            mysqli_stmt_close($stmt);
        } else {
            $error_message = 'Oops! Something went wrong. Please try again later.';
        }
    }
}

// Fetch reservation details for pre-filling the form
$reservation = array(
    'reservationid' => '',
    'status' => ''
);

if (isset($_GET['reservationid'])) {
    $reservationid = $_GET['reservationid'];

    $selectQuery = "SELECT * FROM reservations WHERE reservationid = ?";
    $stmt = mysqli_prepare($link, $selectQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $reservationid);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        $fetchedReservation = mysqli_fetch_assoc($result);
        
        if ($fetchedReservation) {
            $reservation = $fetchedReservation;
        }
        
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Reservation</title>
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
        <h1>Update Reservation</h1>
        <?php if (!empty($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
        <?php if (!empty($success_message)) { ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateReservationForm()">
            <input type="hidden" name="reservationid" value="<?php echo $reservation['reservationid']; ?>">
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="Confirmed" <?php if ($reservation['status'] === 'Confirmed') echo 'selected'; ?>>Confirmed</option>
                <option value="Cancelled" <?php if ($reservation['status'] === 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                <option value="Pending" <?php if ($reservation['status'] === 'Pending') echo 'selected'; ?>>Pending</option>
            </select>
            <br>
            <button type="submit">Update Reservation</button>
        </form>
    </div>
    <footer>
        <p>&copy; CinemaApp 2023. All rights reserved.</p>
    </footer>
</body>
</html>
