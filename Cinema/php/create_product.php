<?php
session_start();
require_once "config.php";

// Check if user is not logged in, then redirect to login page
// Αν ο χρήστης δεν έχει συνδεθεί τον πάμε στην σελίδα login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.html');
    exit();
}

// Check if user has admin role, then allow access
// Να επιτρέπεται η πρόσβαση μόνο αν ο χρήστης είναι admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php'); 
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $type = $_POST['type'];

    $insertQuery = "INSERT INTO products (name, description, price, type)
                    VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($link, $insertQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssds", $name, $description, $price, $type);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: index.php'); // Redirect to index page after successful addition | // Ανακατεύθυνση στην αρχική μετά από επιτυχημένο addition
            exit();
        } else {
            $error_message = 'Failed to add movie. Please try again.';
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
    <title>Add Movies</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/script.js"></script>
</head>
<body>
    <header>
        <h1>
            <a name="logo" href="index.php">CinemaApp</a>
        </h1>
        <!-- Navigation links based on user role and login status -->
        <!-- navigation links βάσει του ρόλου του χρήστη και του login status -->
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
        <h1>Add Movies</h1>
        <?php if (!empty($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateMovieForm()">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" required>
            <br>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>
            <br>
            <label for="type">Type:</label>
            <input type="text" id="type" name="type" required>
            <br>
            <button type="submit">Add Movie</button>
        </form>
    </div>
    <footer>
        <p>&copy; CinemaApp 2023. All rights reserved.</p>
    </footer>
</body>
</html>
