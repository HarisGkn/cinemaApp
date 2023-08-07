<?php
session_start();
require_once "config.php";

$error_message = '';

// Check if user is not logged in, then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = $_SESSION['userid'];
    $productid = $_POST['product'];
    $reservationDate = $_POST['date'];
    $status = $_POST['status'];

    $insertQuery = "INSERT INTO reservations (userid, productid, reservationdate, status)
                    VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($link, $insertQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iiss", $userid, $productid, $reservationDate, $status);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: confirmation.html');
            exit();
        } else {
            $error_message = 'Reservation failed. Please try again.';
        }

        mysqli_stmt_close($stmt);
    } else {
        $error_message = 'Oops! Something went wrong. Please try again later.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Make a Reservation</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="js/script.js"></script>
</head>
<body>
    <header>
        <h1>CinemaApp</h1>
        <nav>
            <a href="index.html">Home</a>
            <a href="products.html">Movies</a>
            <a href="login.html">Login</a>
            <a href="register.html">Register</a>
            <a href="reservation.html">Make Reservation</a>
            <a href="reservations.html">View Reservations</a>
        </nav>
    </header>
    <div class="container">
        <h1>Make a Reservation</h1>
        <?php if (!empty($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateReservationForm()">
            <label for="product">Product:</label>
            <select id="product" name="product" required>
                <option value="1">Lawrence of Arabia</option>
                <option value="2">Rambo - First Blood</option>
                <option value="3">Beasts of No Nation</option>
                <option value="4">The Last Samurai</option>
                <option value="5">2 Fast 2 Furious</option>
                <option value="6">Avatar</option>
            </select>
            <br>
            <label for="date">Reservation Date:</label>
            <input type="date" id="date" name="date" required>
            <br>
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="active">Active</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <br>
            <button type="submit">Make Reservation</button>
        </form>
    </div>
    <footer>
        <p>&copy; CinemaApp 2023. All rights reserved.</p>
    </footer>
</body>
</html>
