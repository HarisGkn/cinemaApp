<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

// Fetch movies from the database
// fetch τις ταινίες απο τη βάση
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
    // Παίρνουμε το userid απο το session 
    $userid = $_SESSION['userid'];

    $postData = http_build_query(array(
        'userid' => $userid,
        'productid' => $productid,
        'reservationdate' => $reservationdate,
        'status' => $status
    ));

    // Define the Java service endpoint URL
    // Με αυτό το request θα αλληλεπιδρούμε με τη βάση
    $endpointUrl = 'http://localhost:8080/CinemaService/reservations/create';

    // Create a context for the HTTP POST request
    // ουσιαστικά ξεκαθαρίζουμε ότι το request θα είναι post κλπ
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
