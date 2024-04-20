function openNav() {
    document.getElementById("nav_shrink").style = "visibility: visible";
    document.getElementById("nav_expand").style = "visibility: hidden";
    document.getElementById("content").style.left = "70px";
}
function closeNav() {
    document.getElementById("nav_shrink").style = "visibility: hidden";
    document.getElementById("nav_expand").style = "visibility: visible";
    document.getElementById("content").style.left = "220px";
}
function adjustContentWidth() {
    var navWidth = document.getElementById("nav").offsetWidth;
    var windowWidth = window.innerWidth;
    var contentWidth = windowWidth - navWidth;
    document.getElementById("content").style.width = contentWidth + "px";
}
function AddSecurity() {
    document.getElementById("mod").style = "visibility: visible";
    document.getElementById("gate").style = "visibility: hidden";
    document.getElementById("secure").style = "visibility: visible";
    document.getElementById("unsecure").style = "visibility: hidden";

    // Execute the SQL query via AJAX
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Insertion successful, do something if needed
                console.log('Data inserted successfully');
            } else {
                console.error('Failed to insert data:', xhr.status);
            }
        }
    };
    xhr.open('POST', 'add_security.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send();
}

// Adjust content width when the window is resized
window.onresize = function (event) {
    adjustContentWidth();
}
var elements = document.querySelectorAll('.icon');

// Iterate through each element and add the mouseover event listener
elements.forEach(function (element) {
    element.addEventListener('mouseover', function () {
        element.style.filter = 'drop-shadow(0 0 0.75rem yellow)';
        element.style.height = '25px';
    });

    // Add mouseout event listener to revert the background color
    element.addEventListener('mouseout', function () {
        element.style.filter = '';
        element.style.height = '20px';
    });
});
var elements = document.querySelectorAll('.options');

// Iterate through each element and add the mouseover event listener
elements.forEach(function (element) {
    element.addEventListener('mouseover', function () {
        element.style.filter = 'drop-shadow(0 0 0.75rem yellow)';
        element.style.width = '90px';
        element.style.color = 'white';
    });

    // Add mouseout event listener to revert the background color
    element.addEventListener('mouseout', function () {
        element.style.filter = '';
        element.style.width = '80px';
        element.style.color = 'rgba(255, 255, 255, 0.719)';
    });
});

document.getElementById("room").addEventListener("change", function () {
    var selectedOption = this.value;
    var imageSrc = '';

    // Check if the selected option is the default one
    if (selectedOption === "Choose a Room") {
        // Set the image source to the default picture
        imageSrc = "room.jpg";
    } else {
        // Get the data-image attribute of the selected option
        imageSrc = this.options[this.selectedIndex].getAttribute("data-image");
    }

    // Update the image source
    document.getElementById("displayedImage").src = imageSrc;
});

// Add event listener to the displayed image
document.getElementById("displayedImage").addEventListener("click", function () {
    // Specify the URL of the page you want to open in a new tab/window
    var newPageUrl = "Rooms.php";

    // Open the new page in a new tab/window
    window.open(newPageUrl, "_self");
});


function openPopup() {
    document.getElementById('popup').style.display = 'block';
    fetchNotifications();
}

function closePopup() {
    document.getElementById('popup').style.display = 'none';
}

function fetchNotifications() {
    fetch('get_notifications.php')
        .then(response => response.json())
        .then(data => {
            const notificationList = document.getElementById('notification-list');
            notificationList.innerHTML = ''; // Clear previous notifications
            data.forEach(notification => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${notification.NotificationID}</td>
                    <td>${notification.Message}</td>
                    <td>${notification.CreatedAt}</td>
                    <td><img src="delete.png" width="20px" height="20px" alt="Remove" class="remove-icon" onclick="removeNotification(${notification.NotificationID}, this)"></td>
                `;
                notificationList.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching notifications:', error));
}

function removeNotification(notificationID, button) {
    const confirmation = confirm("Are you sure you want to remove this notification?");
    if (confirmation) {
        fetch('remove_notification.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ notificationID })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const notificationRow = button.closest('tr');
                    notificationRow.classList.add('remove-animation');
                    notificationRow.addEventListener('animationend', () => {
                        notificationRow.remove(); // Remove the row from the table after animation
                    });
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error removing notification:', error));
    }
}

// Function to redirect to the desired page
function redirectToPage() {
    window.location.href = "TimeConsumption.php";
}
// Function to redirect to the desired page
function redirectPage() {
    window.location.href = "EnergyConsumption.php";
}

const apiKey = "ef2287dc68e0b17620acb9467675eb24";
const apiUrl = "https://api.openweathermap.org/data/2.5/weather?units=metric&q=";
const searchBox = document.querySelector('.search input');
const searchBtn = document.querySelector('.search button');
const weatherIcon = document.querySelector('.weather-icon');

async function checkWeather(city) {
    const response = await fetch(apiUrl + city + `&appid=${apiKey}`);

    if (response.status == 404) {
        document.querySelector('.error').style.display = "block";
        document.querySelector('.weather').style.display = "none";
    }
    else {
        var data = await response.json();
        console.log(data);
        document.querySelector('.city').innerHTML = data.name;
        document.querySelector('.temp').innerHTML = Math.round(data.main.temp) + "°C";
        document.querySelector('.humidity').innerHTML = data.main.humidity + "%";
        document.querySelector('.wind').innerHTML = data.wind.speed + " km/h";

        if (data.weather[0].main == "Clouds") {
            weatherIcon.src = 'clouds.png';
        }
        else if (data.weather[0].main == "Clear") {
            weatherIcon.src = 'clear.png';
        }
        else if (data.weather[0].main == "Rain") {
            weatherIcon.src = 'rain.png';
        }
        else if (data.weather[0].main == "Drizzle") {
            weatherIcon.src = 'drizzle.png';
        }
        else if (data.weather[0].main == "Mist") {
            weatherIcon.src = 'mist.png';
        }

        document.querySelector(".weather").style.display = "block";
        document.querySelector(".error").style.display = "none";
    }
}

searchBtn.addEventListener('click', () => {
    checkWeather(searchBox.value);
})

function handleKeyPress(event) {
    if (event.key == "Enter") {
        checkWeather(searchBox.value);
    }
}
document.addEventListener('keydown', handleKeyPress);
