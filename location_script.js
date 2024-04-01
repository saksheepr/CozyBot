var map;

document.addEventListener('DOMContentLoaded', function() {
    // Fetch user's location and set the map view
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var userLatitude = position.coords.latitude;
            var userLongitude = position.coords.longitude;
            map = L.map('map').setView([userLatitude, userLongitude], 10); // Set the map view to user's location
            
            // Add a tile layer to the map
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            // Add event listener to the button
            document.getElementById('displayAllMembersBtn').addEventListener('click', function(event) {
                event.preventDefault();
                fetchMemberCoordinates();
            });
        }, function(error) {
            console.error('Error getting user location:', error);
        });
    } else {
        console.error('Geolocation is not supported by your browser');
    }
});

function displayAllMembers() {
    fetch('get_member_coordinates.php')
        .then(response => response.json())
        .then(memberCoordinates => {
            console.log('Received member coordinates from server:', memberCoordinates);
            displayMemberCoordinates(memberCoordinates);
        })
        .catch(error => console.error('Error fetching member coordinates:', error));
}
function displayMemberCoordinates(memberCoordinates) {
    console.log("Received member coordinates from server:", memberCoordinates);
    // Clear existing markers and circles from the map
    map.eachLayer(function (layer) {
        if (layer instanceof L.Marker || layer instanceof L.Circle) {
            map.removeLayer(layer);
        }
    });

    // Add markers for each member's coordinates
    memberCoordinates.forEach(function (member, index) {
        console.log("Member " + index + " object:", member);
        if (member.hasOwnProperty('home_lat') && member.hasOwnProperty('home_long')) {
            // This is the home location object
            var homeLatitude = parseFloat(member.home_lat);
            var homeLongitude = parseFloat(member.home_long);
            if (!isNaN(homeLatitude) && !isNaN(homeLongitude)) {
                var homeMarkerIcon = L.icon({
                    iconUrl: 'home-marker.png',
                    iconSize: [32, 32], // adjust the size as needed
                    iconAnchor: [16, 32], // center the icon on its point
                });
                var homeMarker = L.marker([homeLatitude, homeLongitude], { icon: homeMarkerIcon }).addTo(map);
                homeMarker.bindPopup('Home Location: Latitude: ' + homeLatitude + ', Longitude: ' + homeLongitude);

                // Add a circle around the home location with a radius of 10km
                L.circle([homeLatitude, homeLongitude], {
                    color: 'blue', // Adjust color as needed
                    fillColor: 'blue', // Adjust fill color as needed
                    fillOpacity: 0.3, // Adjust opacity as needed
                    radius: 10000 // 10km radius
                }).addTo(map);
            } else {
                console.error('Invalid coordinates for home location:', member);
            }
        } else {
            // This is a regular member object
            var latitude = parseFloat(member.Latitude);
            var longitude = parseFloat(member.Longitude);
            if (!isNaN(latitude) && !isNaN(longitude)) {
                var memberMarkerIcon = L.icon({
                    iconUrl: 'member-marker.png',
                    iconSize: [32, 32], // adjust the size as needed
                    iconAnchor: [16, 32], // center the icon on its point
                });
                var memberMarker = L.marker([latitude, longitude], { icon: memberMarkerIcon }).addTo(map);
                memberMarker.bindPopup('Member: ' + member.MemberName + '<br>Latitude: ' + latitude + '<br>Longitude: ' + longitude);
            } else {
                console.error('Invalid coordinates for member:', member);
            }
        }
    });
}







function fetchMemberCoordinates() {
    // Make an AJAX request to fetch member coordinates from the server
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            console.log('Response from server:', this.responseText); // Log the response text
            if (this.status == 200) {
                // Parse the JSON response
                var memberCoordinates = JSON.parse(this.responseText);
                // Display the member coordinates on the map
                displayMemberCoordinates(memberCoordinates);
            } else {
                console.error('Error fetching member coordinates:', this.statusText);
            }
        }
    };
    xhttp.open("GET", "get_member_coordinates.php", true);
    xhttp.send();
}
