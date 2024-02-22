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

// Form submission handling
document.getElementById('deviceForm').addEventListener('submit', function(event) {
  event.preventDefault();
  // Get form values
  var deviceName = document.getElementById('deviceName').value;
  var deviceType = document.getElementById('deviceType').value;
  var deviceDetailsHTML = "<div class='devicedetails'>"; // Start new devicedetails div
  
  if (deviceType === 'Lights') {
    deviceDetailsHTML += "<img class='icontype' src='bulb.png' >";
  } else if (deviceType === 'Doors') {
    deviceDetailsHTML += "<img class='icontype' src='door.png' >";
  } else if (deviceType === 'Fans') {
    deviceDetailsHTML += "<img class='icontype' src='fan.png' >";
  }else if (deviceType === 'Thermostat') {
    deviceDetailsHTML += "<img class='icontype' src='thermostat.png' >";
  }else if (deviceType === 'Ac') {
    deviceDetailsHTML += "<img class='icontype' src='ac.png' >";
  }else if (deviceType === 'Plugs') {
    deviceDetailsHTML += "<img class='icontype' src='plug.png' >";
  }
  deviceDetailsHTML += "<p id='dev'>" + deviceName + "</p>";

  deviceDetailsHTML += "</div>"; // End devicedetails div

  // Append new devicedetails div to the tab
  var tabContainer = document.getElementById('tab');
  var newDeviceDetailsDiv = document.createElement('div');
  newDeviceDetailsDiv.innerHTML = deviceDetailsHTML;
  tabContainer.appendChild(newDeviceDetailsDiv);

  // Clear the form
  document.getElementById('deviceForm').reset();

  // Close the popup
  closePopup();

  // Scroll to the bottom
  tabContainer.scrollTop = tabContainer.scrollHeight;
});