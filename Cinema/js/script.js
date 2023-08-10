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


document.addEventListener("DOMContentLoaded", function () {
    const countrySelect = document.getElementById("country");
    const citySelect = document.getElementById("city");

    // Update city dropdown based on selected country
    countrySelect.addEventListener("change", function () {
        const selectedCountry = countrySelect.value;

        // Clear city dropdown options
        citySelect.innerHTML = '<option value="">Select City</option>';

        if (selectedCountry) {
            fetchCities(selectedCountry);
        }
    });
});

function fetchCities(country) {
    const citiesEndpoint = 'https://countriesnow.space/api/v0.1/countries/cities';
    const requestData = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            country: country,
        }),
    };

    fetch(citiesEndpoint, requestData)
        .then(response => response.json())
        .then(data => {
            if (!data.error && data.data) {
                const cities = data.data;
                const citySelect = document.getElementById("city");

                cities.forEach(function (city) {
                    const option = document.createElement("option");
                    option.value = city;
                    option.textContent = city;
                    citySelect.appendChild(option);
                });
            } else {
                console.error('Error fetching cities:', data.msg);
            }
        })
        .catch(error => console.error('Error fetching cities:', error));
}

