var elements = document.querySelectorAll('.icon');

// Iterate through each element and add the mouseover event listener
elements.forEach(function(element) {
    element.addEventListener('mouseover', function() {
        element.style.filter = 'drop-shadow(0 0 0.75rem yellow)';
        element.style.height = '25px';
    });

    // Add mouseout event listener to revert the background color
    element.addEventListener('mouseout', function() {
        element.style.filter = '';
        element.style.height = '20px';
    });
});
// Function to fetch devices based on selected room
document.getElementById('RoomName').addEventListener('change', function() {
    console.log("Room : ",document.getElementById('RoomName').value);
    var selectedRoom = this.value;
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var devices = JSON.parse(this.responseText);
            var deviceSelect = document.getElementById('DeviceName');
            deviceSelect.innerHTML = ''; // Clear previous options
            devices.forEach(function(device) {
                var option = document.createElement('option');
                option.value = device.DeviceName;
                option.textContent = device.DeviceName;
                deviceSelect.appendChild(option);
            });
        }
    };
    xhr.open('GET', 'get_devices.php?room=' + selectedRoom, true);
    xhr.send();
});
