<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <!-- Include the header -->
    <header>
        <h1>CinemaApp</h1>
        <nav>
            <a href="index.html">Home</a>
            <a href="products.html">Movies</a>
            <a href="login.html">Login</a>
            <a href="register.html">Register</a>
            <a href="reservation.html">Make Reservation</a>
            <a href="reservations.html">View Reservations</a>
            <!-- Add more navigation links as needed -->
        </nav>
    </header>
    
    <h1>Available Movies</h1>
    <div id="product-list">
        <!-- Movie details will be dynamically loaded here -->
    </div>
    
    <!-- Include the footer -->
    <footer>
        <p>&copy; CinemaApp 2023. All rights reserved.</p>
    </footer>

    <script src="js/script.js"></script>
    <script>
        // Use JavaScript to fetch and display movie data
        fetchMovies();
    </script>
</body>
</html>

