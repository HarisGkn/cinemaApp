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

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productid = $_POST['productid'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $type = $_POST['type'];

    $updateQuery = "UPDATE products 
                    SET name = ?, description = ?, price = ?, type = ?
                    WHERE productid = ?";
    
    $stmt = mysqli_prepare($link, $updateQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssdsi", $name, $description, $price, $type, $productid);

        if (mysqli_stmt_execute($stmt)) {
            $success_message = 'Product updated successfully.';
        } else {
            $error_message = 'Failed to update product. Please try again.';
        }

        mysqli_stmt_close($stmt);
    } else {
        $error_message = 'Oops! Something went wrong. Please try again later.';
    }
}

// Fetch product details for pre-filling the form
$product = array(
    'productid' => '',
    'name' => '',
    'description' => '',
    'price' => '',
    'type' => ''
);

if (isset($_GET['productid'])) {
    $productid = $_GET['productid'];

    $selectQuery = "SELECT * FROM products WHERE productid = ?";
    $stmt = mysqli_prepare($link, $selectQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $productid);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        $fetchedProduct = mysqli_fetch_assoc($result);
        
        if ($fetchedProduct) {
            $product = $fetchedProduct;
        }
        
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Movie</title>
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
        <h1>Update Movie</h1>
        <?php if (!empty($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
        <?php if (!empty($success_message)) { ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateMovieForm()">
            <input type="hidden" name="productid" value="<?php echo $product['productid']; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required>
            <br>
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" value="<?php echo $product['description']; ?>" required>
            <br>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
            <br>
            <label for="type">Type:</label>
            <input type="text" id="type" name="type" value="<?php echo $product['type']; ?>" required>
            <br>
            <button type="submit">Update Movie</button>
        </form>
    </div>
    <footer>
        <p>&copy; CinemaApp 2023. All rights reserved.</p>
    </footer>
</body>
</html>
