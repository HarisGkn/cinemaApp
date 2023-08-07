<?php
session_start();
require_once "config.php";

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate user input (similar to registration validation)
    if (empty($username) || empty($password)) {
        $error_message = 'Please fill in all fields.';
    } else {
        // Prepare and execute a SQL query to check user credentials
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($link, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) === 1) {
                $user = mysqli_fetch_assoc($result);

                if (password_verify($password, $user['password'])) {
                    // User is authenticated, set session and redirect
                    $_SESSION['loggedin'] = true;
                    $_SESSION['userid'] = $user['userid']; // Store user ID in session
                    $_SESSION['role'] = $user['role']; // Store user role in session

                    // Check if the user is the admin
                    if ($user['role'] === 'admin') {
                        $_SESSION['admin'] = true;
                        header('Location: index.php'); // Redirect admin to admin area
                    } else {
                        header('Location: index.php'); // Redirect non-admin to regular area
                    }
                    exit();
                } else {
                    $error_message = 'Invalid username or password. Please try again.';
                }
            } else {
                $error_message = 'Invalid username or password. Please try again.';
            }
        } else {
            $error_message = 'Oops! Something went wrong. Please try again later.';
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="script.js"></script>
</head>
<body>
    <!-- Include the header -->
    <header>
        <h1>CinemaApp</h1>
        <nav>
        <a href="index.php">Home</a>
        <a href="view_movies.php">list Movies</a>
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
        <h1>Login</h1>
        <?php if (!empty($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
        <form action="login.php" method="post" onsubmit="return validateLoginForm()">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit">Login</button>
        </form>
    </div>
    <!-- Include the footer -->
    <footer>
        <p>&copy; CinemaApp 2023. All rights reserved.</p>
    </footer>
</body>
</html>
