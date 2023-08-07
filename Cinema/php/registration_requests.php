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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve']) && isset($_POST['request_id'])) {
        $request_id = $_POST['request_id'];

        $selectQuery = "SELECT * FROM registration_requests WHERE requestid = ?";
        $stmtSelectRequest = mysqli_prepare($link, $selectQuery);

        if ($stmtSelectRequest) {
            mysqli_stmt_bind_param($stmtSelectRequest, "i", $request_id);
            mysqli_stmt_execute($stmtSelectRequest);
            
            $result = mysqli_stmt_get_result($stmtSelectRequest);
            $request = mysqli_fetch_assoc($result);
            
            if ($request) {
                $insertQuery = "INSERT INTO users (firstname, lastname, country, city, address, email, username, password, role)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'user')";

                $stmtInsertUser = mysqli_prepare($link, $insertQuery);

                if ($stmtInsertUser) {
                    mysqli_stmt_bind_param($stmtInsertUser, "ssssssss", $request['firstname'], $request['lastname'], $request['country'],
                        $request['city'], $request['address'], $request['email'], $request['username'], $request['password']);
                    
                    if (mysqli_stmt_execute($stmtInsertUser)) {
                        $updateQuery = "UPDATE registration_requests SET status = 'accepted' WHERE requestid = ?";
                        $stmtUpdateRequest = mysqli_prepare($link, $updateQuery);
                        
                        if ($stmtUpdateRequest) {
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
        }
    } elseif (isset($_POST['reject']) && isset($_POST['request_id'])) {
        $request_id = $_POST['request_id'];

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
        <h1>Admin Approval</h1>
        <?php if (!empty($approval_message)) { ?>
            <p class="success"><?php echo $approval_message; ?></p>
        <?php } ?>
        <?php if (!empty($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
        <?php if (!empty($requests)) { ?>
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
