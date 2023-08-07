<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>CinemaApp</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <!-- Include the header -->
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
        <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                    echo '<h2>Welcome, Admin!</h2>';
                    echo '<h2>From here you have access to modify movies and change the status of reservations</h2>';
                } else {
                    echo '<h2>Welcome, User!</h2>';
                    echo '<p>Enjoy using CinemaApp to make movie reservations.</p>';
                }
            } else {
                echo '<h2>Welcome to CinemaApp</h2>';
                echo '<h2>Your ultimate movie reservation platform.</h2>';
            }
        ?>
    </div>
    <!-- Include the footer -->
    <footer>
        <p>&copy; CinemaApp 2023. All rights reserved.</p>
    </footer>
</body>
</html>