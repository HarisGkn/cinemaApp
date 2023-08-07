<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userid'];
$query = "SELECT * FROM users WHERE userid = ?";
$stmt = mysqli_prepare($link, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
} else {
    die("Error in fetching user information");
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Info</title>
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
        <h1>My Info</h1>
        <?php if (isset($_GET['success']) && $_GET['success'] === "true") { ?>
            <p class="success">Information updated successfully.</p>
        <?php } ?>
        <table>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>First Name</td>
                <td><?php echo $user['firstname']; ?></td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td><?php echo $user['lastname']; ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo $user['email']; ?></td>
            </tr>
            <tr>
                <td>Country</td>
                <td><?php echo $user['country']; ?></td>
            </tr>
            <tr>
                <td>City</td>
                <td><?php echo $user['city']; ?></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><?php echo $user['address']; ?></td>
            </tr>
        </table>
    </div>
    <footer>
        <p>&copy; CinemaApp 2023. All rights reserved.</p>
    </footer>
</body>
</html>
