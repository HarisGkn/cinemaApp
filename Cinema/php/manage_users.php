<?php
session_start();
require_once "config.php";

// Check if user is not logged in or not an admin, then redirect
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Fetch users from the database
$selectQuery = "SELECT userid, firstname, lastname, country, city, address, email, username, role FROM users";
$result = mysqli_query($link, $selectQuery);

$users = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}

// Delete user if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $userid = $_POST['userid'];

    $deleteQuery = "DELETE FROM users WHERE userid = ?";
    $stmt = mysqli_prepare($link, $deleteQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $userid);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: manage_users.php');
            exit();
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
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
        <h1>Manage Users</h1>
        <table>
            <tr>
                <th>User ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Country</th>
                <th>City</th>
                <th>Address</th>
                <th>Email</th>
                <th>Username</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?php echo $user['userid']; ?></td>
                    <td><?php echo $user['firstname']; ?></td>
                    <td><?php echo $user['lastname']; ?></td>
                    <td><?php echo $user['country']; ?></td>
                    <td><?php echo $user['city']; ?></td>
                    <td><?php echo $user['address']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <form class="action-form" method="post">
                            <input type="hidden" name="userid" value="<?php echo $user['userid']; ?>">
                            <button type="submit" name="delete_user">Delete</button>
                        </form>
                        <form class="action-form" action="update_user.php" method="get">
                            <input type="hidden" name="userid" value="<?php echo $user['userid']; ?>">
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
