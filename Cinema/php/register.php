<?php
session_start();
require_once "config.php";

// Initialize variables
$firstname = $lastname = $country = $city = $address = $email = $username = $password = "";
$firstname_err = $lastname_err = $country_err = $city_err = $address_err = $email_err = $username_err = $password_err = "";
$success_message = $error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input from the form
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $country = trim($_POST["country"]);
    $city = trim($_POST["city"]);
    $address = trim($_POST["address"]);
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate form inputs
    if (empty($firstname)) {
        $firstname_err = "Please enter your first name.";
    }

    if (empty($lastname)) {
        $lastname_err = "Please enter your last name.";
    }

    if (empty($country)) {
        $country_err = "Please enter your country.";
    }

    if (empty($city)) {
        $city_err = "Please enter your city.";
    }

    if (empty($address)) {
        $address_err = "Please enter your address.";
    }

    if (empty($email)) {
        $email_err = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    }

    // Check if the username is already taken
    if (empty($username)) {
        $username_err = "Please choose a username.";
    } else {
        $sql = "SELECT userid FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    if (empty($password)) {
        $password_err = "Please enter a password.";
    } elseif (strlen($password) < 8) {
        $password_err = "Password must have at least 8 characters.";
    }

    // If there are no validation errors, insert registration request into the database
    if (empty($firstname_err) && empty($lastname_err) && empty($country_err) && empty($city_err) && empty($address_err) && empty($email_err) && empty($username_err) && empty($password_err)) {
        $sql = "INSERT INTO registration_requests (firstname, lastname, country, city, address, email, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssssss", $param_firstname, $param_lastname, $param_country, $param_city, $param_address, $param_email, $param_username, $param_password);

            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_country = $country;
            $param_city = $city;
            $param_address = $address;
            $param_email = $email;
            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Registration request submitted. An admin will review your request.";
            } else {
                $error_message = "Registration request failed. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($link);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CinemaApp</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body class="bg-primary">
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
        <div class="form-container">
            <h2>Register</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo $firstname; ?>">
                <span class="error"><?php echo $firstname_err; ?></span>
                <br>

                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>">
                <span class="error"><?php echo $lastname_err; ?></span>
                <br>

                <label for="country">Country:</label>
                <input type="text" id="country" name="country" value="<?php echo $country; ?>">
                <span class="error"><?php echo $country_err; ?></span>
                <br>

                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo $city; ?>">
                <span class="error"><?php echo $city_err; ?></span>
                <br>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo $address; ?>">
                <span class="error"><?php echo $address_err; ?></span>
                <br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>">
                <span class="error"><?php echo $email_err; ?></span>
                <br>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>">
                <span class="error"><?php echo $username_err; ?></span>
                <br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <span class="error"><?php echo $password_err; ?></span>
                <br>

                <button type="submit">Register</button>
            </form>
        </div>
    </div>

    <?php if (!empty($error_message)) { ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php } ?>

    <?php if (!empty($success_message)) { ?>
        <script>window.location.href = "registration_pending.php";</script>
    <?php } ?>
</body>
</html>
