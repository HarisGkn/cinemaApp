<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

// Fetch movies from the database
$selectMoviesQuery = "SELECT productid, name FROM products";
$result = mysqli_query($link, $selectMoviesQuery);

$movies = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $movies[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_reservation'])) {
    $productid = $_POST['productid'];
    $reservationdate = $_POST['reservationdate'];
    $status = 'Pending'; // Default status

    // Retrieve userid from session
    $userid = $_SESSION['userid'];

    $postData = http_build_query(array(
        'userid' => $userid,
        'productid' => $productid,
        'reservationdate' => $reservationdate,
        'status' => $status
    ));

    // Define the Java service endpoint URL
    $endpointUrl = 'http://localhost:8080/CinemaService/reservations/create';

    // Create a context for the HTTP POST request
    $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $postData
        )
    ));

    // Send the HTTP POST request
    $result = file_get_contents($endpointUrl, false, $context);

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Reservation</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>CinemaApp</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="view_movies.php">List Movies</a>
            <a href="view_reservations.php">View Reservations</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <div class="container">
        <h1>Create Reservation</h1>
        <?php if (isset($errorMessage)) { ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php } ?>
        <form method="post">
            <label for="productid">Select a Movie:</label>
            <select name="productid" required>
                <?php foreach ($movies as $movie) { ?>
                    <option value="<?php echo $movie['productid']; ?>"><?php echo $movie['name']; ?></option>
                <?php } ?>
            </select><br>
            <label for="reservationdate">Reservation Date:</label>
            <input type="date" name="reservationdate" required><br>
            <button type="submit" name="create_reservation">Create Reservation</button>
        </form>
    </div>
    <footer>
        <p>&copy; CinemaApp 2023. All rights reserved.</p>
    </footer>
</body>
</html>
