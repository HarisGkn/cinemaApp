<?php
session_start();
require_once "config.php";

// Check if user is not logged in or not an admin, then redirect
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$approval_message = '';
$error_message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle approval of registration request
    if (isset($_POST['approve']) && isset($_POST['request_id'])) {
        $request_id = $_POST['request_id'];

        // Query to retrieve the selected registration request
        $selectQuery = "SELECT * FROM registration_requests WHERE requestid = ?";
        $stmtSelectRequest = mysqli_prepare($link, $selectQuery);

        if ($stmtSelectRequest) {
            // Execute the select query
            mysqli_stmt_bind_param($stmtSelectRequest, "i", $request_id);
            mysqli_stmt_execute($stmtSelectRequest);
            
            // Get the request details from the result
            $result = mysqli_stmt_get_result($stmtSelectRequest);
            $request = mysqli_fetch_assoc($result);
            
            if ($request) {
                // Insert the user into the 'users' table
                $insertQuery = "INSERT INTO users (firstname, lastname, country, city, address, email, username, password, role)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'user')";

                $stmtInsertUser = mysqli_prepare($link, $insertQuery);

                if ($stmtInsertUser) {
                    // Bind parameters and execute the insert query
                    mysqli_stmt_bind_param($stmtInsertUser, "ssssssss", $request['firstname'], $request['lastname'], $request['country'],
                        $request['city'], $request['address'], $request['email'], $request['username'], $request['password']);
                    
                    if (mysqli_stmt_execute($stmtInsertUser)) {
                        // Update the request status to 'accepted'
                        $updateQuery = "UPDATE registration_requests SET status = 'accepted' WHERE requestid = ?";
                        $stmtUpdateRequest = mysqli_prepare($link, $updateQuery);
                        
                        if ($stmtUpdateRequest) {
                            // Bind parameter and execute the update query
                            mysqli_stmt_bind_param($stmtUpdateRequest, "i", $request_id);
                            if (mysqli_stmt_execute($stmtUpdateRequest)) {
                                $approval_message = 'Request approved and user added successfully.';
                            }
                        }
                        mysqli_stmt_close($stmtUpdateRequest);
                    } else {
                        $error_message = 'Failed to approve request. Please try again.';
                    }
                    mysqli_stmt_close($stmtInsertUser);
                } else {
                    $error_message = 'Oops! Something went wrong. Please try again later.';
                }
            }
            
            mysqli_stmt_close($stmtSelectRequest);
        } // Handle rejection of registration request
    } elseif (isset($_POST['reject']) && isset($_POST['request_id'])) {
        $request_id = $_POST['request_id'];
        // Update the request status to 'rejected'
        $updateQuery = "UPDATE registration_requests SET status = 'rejected' WHERE requestid = ?";
        $stmtUpdateRequest = mysqli_prepare($link, $updateQuery);

        if ($stmtUpdateRequest) {
            mysqli_stmt_bind_param($stmtUpdateRequest, "i", $request_id);
            if (mysqli_stmt_execute($stmtUpdateRequest)) {
                $approval_message = 'Request rejected successfully.';
            } else {
                $error_message = 'Failed to reject request. Please try again.';
            }
            mysqli_stmt_close($stmtUpdateRequest);
        } else {
            $error_message = 'Oops! Something went wrong. Please try again later.';
        }
    }
}

$requests = array();

$selectQuery = "SELECT * FROM registration_requests WHERE status = 'pending'";
$result = mysqli_query($link, $selectQuery);

if ($result) {
    // Fetch and store requests in the 'requests' array
    while ($row = mysqli_fetch_assoc($result)) {
        $requests[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Approval</title>
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
        <h1>Admin Approval</h1>
        <!-- Success message -->
        <?php if (!empty($approval_message)) { ?>
            <p class="success"><?php echo $approval_message; ?></p>
        <?php } ?>
        <!-- Error message -->
        <?php if (!empty($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
        <?php if (!empty($requests)) { ?>
            <!-- Display the pending registration requests in a table -->
            <table>
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $request) { ?>
                        <tr>
                            <td><?php echo $request['firstname']; ?></td>
                            <td><?php echo $request['lastname']; ?></td>
                            <td><?php echo $request['country']; ?></td>
                            <td><?php echo $request['city']; ?></td>
                            <td><?php echo $request['address']; ?></td>
                            <td><?php echo $request['email']; ?></td>
                            <td><?php echo $request['username']; ?></td>
                            <td><?php echo $request['status']; ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="request_id" value="<?php echo $request['requestid']; ?>">
                                    <button type="submit" name="approve">Approve</button>
                                    <button type="submit" name="reject">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No pending registration requests.</p>
        <?php } ?>
    </div>
    <footer>
        <p>&copy; CinemaApp 2023. All rights reserved.</p>
    </footer>
</body>
</html>
