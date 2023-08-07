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
    header('Location: index.php'); // Redirect to a different page for non-admin users
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
            header('Location: products.php'); // Redirect to products page after successful addition
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
        <h1>CinemaApp</h1>
        <nav>
            <a href="index.php">Home</a>
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
