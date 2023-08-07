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
    header('Location: products.php');
    exit();
}

// Fetch products from the database
$selectQuery = "SELECT * FROM products";
$result = mysqli_query($link, $selectQuery);

$products = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}

// Delete product if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $productid = $_POST['productid'];

    $deleteQuery = "DELETE FROM products WHERE productid = ?";
    $stmt = mysqli_prepare($link, $deleteQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $productid);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: manage_products.php');
            exit();
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Movies</title>
    <link rel="stylesheet" href="../css/styles.css">
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
        <h1>View Movies</h1>
        <table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Type</th>
                <th>Action</th>
            </tr>
            <?php foreach ($products as $product) { ?>
                <tr>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['description']; ?></td>
                    <td><?php echo $product['price']; ?></td>
                    <td><?php echo $product['type']; ?></td>
                    <td>
                        <form class="action-form" method="post">
                            <input type="hidden" name="productid" value="<?php echo $product['productid']; ?>">
                            <button type="submit" name="delete_product">Delete</button>
                        </form>
                        <form class="action-form" action="update_products.php" method="get">
                            <input type="hidden" name="productid" value="<?php echo $product['productid']; ?>">
                            <button type="submit">Update</button>
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
