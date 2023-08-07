<?php
session_start();
require_once "config.php";

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve updated information from form fields
    $newFirstName = $_POST['newFirstName'];
    $newLastName = $_POST['newLastName'];
    $newEmail = $_POST['newEmail'];

    // Prepare the data for sending to the Java endpoint
    $data = array(
        "firstName" => $newFirstName,
        "lastName" => $newLastName,
        "email" => $newEmail
    );

    // Send the data to the Java endpoint using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost:8080/CinemaAppService/user/user/" . $_SESSION['userid']);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: Bearer your-access-token"
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    // Redirect to a success page or refresh the current page
    header("Location: my_info.php?success=true");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Info</title>
    <link rel="stylesheet" href="../css/styles.css">
    <!-- Add other head elements as needed -->
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
        <h1>My Info</h1>
        <!-- Display success message if updated successfully -->
        <?php if (isset($_GET['success']) && $_GET['success'] === "true") { ?>
            <p class="success">Information updated successfully.</p>
        <?php } ?>
        <!-- Display the form for updating user information -->
        <form action="my_info.php" method="post">
            <!-- Form fields for updating information -->
            <label for="newFirstName">First Name:</label>
            <input type="text" id="newFirstName" name="newFirstName" required>
            <br>
            <label for="newLastName">Last Name:</label>
            <input type="text" id="newLastName" name="newLastName" required>
            <br>
            <label for="newEmail">Email:</label>
            <input type="email" id="newEmail" name="newEmail" required>
            <br>
            <label for="newCountry">Country:</label>
            <input type="text" id="newCountry" name="newCountry" required>
            <br>
            <label for="newCity">City:</label>
            <input type="text" id="newCity" name="newCity" required>
            <br>
            <label for="newAddress">Address:</label>
            <input type="text" id="newAddress" name="newAddress" required>
            <br>
            <button type="submit">Update Info</button>
        </form>

    </div>
    <!-- Include the footer -->
    <footer>
        <p>&copy; CinemaApp 2023. All rights reserved.</p>
    </footer>
</body>
</html>
