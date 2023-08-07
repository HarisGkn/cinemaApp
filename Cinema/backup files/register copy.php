<?php
session_start();
require_once "config.php";

// Initialize variables
$firstname = $lastname = $country = $city = $address = $email = $username = $password = "";
$firstname_err = $lastname_err = $country_err = $city_err = $address_err = $email_err = $username_err = $password_err = "";
$success_message = $error_message = ""; // Messages for success and error

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $country = trim($_POST["country"]);
    $city = trim($_POST["city"]);
    $address = trim($_POST["address"]);
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate and handle errors for each field
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

    if (empty($username)) {
        $username_err = "Please choose a username.";
    } else {
        // Check if username already exists
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


    // Check if there are no errors before inserting into the database
    if (empty($firstname_err) && empty($lastname_err) && empty($country_err) && empty($city_err) && empty($address_err) && empty($email_err) && empty($username_err) && empty($password_err)) {
        $sql = "INSERT INTO registration_requests (firstname, lastname, country, city, address, email, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssssss", $param_firstname, $param_lastname, $param_country, $param_city, $param_address, $param_email, $param_username, $param_password);

            // Hash the password
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
        <h1>CinemaApp</h1>
        <nav>
            <a href="index.php">Home</a>
            <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                    echo '<a href="view_reservations.php">View Reservations</a>';
                    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                        echo '<a href="create_product.php">Add Movies</a>';
                        echo '<a href="manage_products.php">Manage Movies</a>';
                        echo '<a href="manage_users.php">Manage Users</a>';
                        echo '<a href="registration_requests.php">Manage Registration Requests</a>';
                    }
                    echo '<a href="logout.php">Logout</a>';
                } else {
                    echo '<a href="Login.php">Login</a>';
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
                <input type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>" required>
                <span class="error"><?php echo $lastname_err; ?></span>
                <br>

                <label for="country">Country:</label>
                <input type="text" id="country" name="country" value="<?php echo $country; ?>" required>
                <span class="error"><?php echo $country_err; ?></span>
                <br>

                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo $city; ?>" required>
                <span class="error"><?php echo $city_err; ?></span>
                <br>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo $address; ?>" required>
                <span class="error"><?php echo $address_err; ?></span>
                <br>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                <span class="error"><?php echo $username_err; ?></span>
                <br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                <span class="error"><?php echo $email_err; ?></span>
                <br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <span class="error"><?php echo $password_err; ?></span>
                <br>

                <button type="submit">Register</button>
            </form>
        </div>
    </div>

    <!-- Display success or error messages -->
    <?php if (!empty($error_message)) { ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php } ?>

    <?php if (!empty($success_message)) { ?>
        <p class="success"><?php echo $success_message; ?></p>
    <?php } ?>
</body>
</html>
