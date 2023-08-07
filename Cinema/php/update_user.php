<?php
session_start();
require_once "config.php";

// Check if user is not logged in or not an admin, then redirect
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = $_POST['userid'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    $updateQuery = "UPDATE users 
                    SET firstname = ?, lastname = ?, country = ?, city = ?, address = ?, email = ?, username = ?, role = ?
                    WHERE userid = ?";
    
    $stmt = mysqli_prepare($link, $updateQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssssi", $firstname, $lastname, $country, $city, $address, $email, $username, $role, $userid);

        if (mysqli_stmt_execute($stmt)) {
            $success_message = 'User updated successfully.';
        } else {
            $error_message = 'Failed to update user. Please try again.';
        }

        mysqli_stmt_close($stmt);
    } else {
        $error_message = 'Oops! Something went wrong. Please try again later.';
    }
}

// Fetch user details for pre-filling the form
$user = array(
    'userid' => '',
    'firstname' => '',
    'lastname' => '',
    'country' => '',
    'city' => '',
    'address' => '',
    'email' => '',
    'username' => '',
    'role' => ''
);

if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];

    $selectQuery = "SELECT * FROM users WHERE userid = ?";
    $stmt = mysqli_prepare($link, $selectQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $userid);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        $fetchedUser = mysqli_fetch_assoc($result);
        
        if ($fetchedUser) {
            $user = $fetchedUser;
        }
        
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
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
        <h1>Update User</h1>
        <?php if (!empty($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
        <?php if (!empty($success_message)) { ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateUserForm()">
            <input type="hidden" name="userid" value="<?php echo $user['userid']; ?>">
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo $user['firstname']; ?>" required>
            <br>
            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" value="<?php echo $user['lastname']; ?>" required>
            <br>
            <label for="country">Country:</label>
            <input type="text" id="country" name="country" value="<?php echo $user['country']; ?>" required>
            <br>
            <label for="city">City:</label>
            <input type="text" id="city" name="city" value="<?php echo $user['city']; ?>" required>
            <br>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo $user['address']; ?>" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            <br>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            <br>
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="user" <?php if ($user['role'] === 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
            </select>
            <br>
            <button type="submit">Update User</button>
        </form>
    </div>
    <footer>
        <p>&copy; CinemaApp 2023. All rights reserved.</p>
    </footer>
</body>
</html>
