function validateLoginForm() {
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    if (username === '' || password === '') {
        alert('Please fill in all fields.');
        return false;
    }
    return true;
}

function fetchMovies() {
    fetch('browse.php')
        .then(response => response.json())
        .then(movies => {
            const productList = document.getElementById('product-list');
            movies.forEach(movie => {
                const movieElement = document.createElement('div');
                movieElement.textContent = `Movie: ${movie.name}, Price: $${movie.price.toFixed(2)}`;
                productList.appendChild(movieElement);
            });
        })
        .catch(error => console.error('Error fetching movies:', error));
}

function fetchReservations() {
    fetch('view_reservation.php')
        .then(response => response.json())
        .then(reservations => {
            const reservationList = document.getElementById('reservation-list');
            reservations.forEach(reservation => {
                const reservationElement = document.createElement('div');
                reservationElement.textContent = `Reservation ID: ${reservation.reservationid}, Movie: ${reservation.product_name}, Date: ${reservation.reservationdate}, Status: ${reservation.status}`;
                reservationList.appendChild(reservationElement);
            });
        })
        .catch(error => console.error('Error fetching reservations:', error));
}