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

// Function to open the popup
function openPopup() {
  document.getElementById('overlay').style.display = 'block';
  document.getElementById('popup').style.display = 'block';
}

// Function to close the popup
function closePopup() {
  document.getElementById('overlay').style.display = 'none';
  document.getElementById('popup').style.display = 'none';
}

// Show existing room list and hide new room input
function showExistingRooms() {
  document.getElementById('existingRoomList').style.display = 'block';
  document.getElementById('newRoomInput').style.display = 'none';
}

// Show new room input and hide existing room list
function showNewRoom() {
  document.getElementById('newRoomInput').style.display = 'block';
  document.getElementById('existingRoomList').style.display = 'none';
}

// Function to filter and rearrange device details based on selected device type
document.getElementById('devices').addEventListener('change', function() {
  var selectedDeviceType = this.value; // Get the selected device type
  var deviceDetailsDivs = document.querySelectorAll('.devicedetails'); // Get all device details divs

  // Loop through all device details divs and update visibility based on selected device type
  deviceDetailsDivs.forEach(function(div) {
      if (selectedDeviceType === 'All Devices') {
          // Show all device details if 'All Devices' is selected
          div.style.display = 'flex';
      } else if (div.dataset.deviceType === selectedDeviceType) {
          // Show the device details if it matches the selected device type
          div.style.display = 'flex';
      } else {
          // Hide the device details if it doesn't match the selected device type
          div.style.display = 'none';
      }
  });
});

// Function to update the total number of devices
function updateDeviceCount() {
  var deviceCount = document.querySelectorAll('.devicedetails').length;
  document.getElementById('deviceCount').textContent = deviceCount;
}

// Form submission handling
document.getElementById('deviceForm').addEventListener('submit', function(event) {
  event.preventDefault();

  // Get form values
  var deviceName = document.getElementById('deviceName').value;
  var deviceType = document.getElementById('deviceType').value;

  // Create device details HTML
  var deviceDetailsHTML = "<div class='devicedetails' data-device-type='" + deviceType + "'>";
  if (deviceType === 'Lights') {
      deviceDetailsHTML += "<img class='icontype' src='bulb.png' >";
  } else if (deviceType === 'Doors') {
      deviceDetailsHTML += "<img class='icontype' src='door.png' >";
  } else if (deviceType === 'Fans') {
      deviceDetailsHTML += "<img class='icontype' src='fan.png' >";
  } else if (deviceType === 'Thermostat') {
      deviceDetailsHTML += "<img class='icontype' src='thermostat.png' >";
  } else if (deviceType === 'Ac') {
      deviceDetailsHTML += "<img class='icontype' src='ac.png' >";
  } else if (deviceType === 'Plugs') {
      deviceDetailsHTML += "<img class='icontype' src='plug.png' >";
  }
  deviceDetailsHTML += "<p id='dev'>" + deviceName + "</p></div>";

  // Append new device details to the container
  var tabContainer = document.getElementById('tab');
  tabContainer.insertAdjacentHTML('beforeend', deviceDetailsHTML);

  // Update the total number of devices
  updateDeviceCount();

  // Clear the form
  document.getElementById('deviceForm').reset();

  // Close the popup
  closePopup();

  // Scroll to the bottom
  tabContainer.scrollTop = tabContainer.scrollHeight;
});

// Function to update the total number of devices when the page loads
window.addEventListener('load', function() {
  updateDeviceCount();
});
