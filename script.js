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

// Function to update the total number of devices
function updateDeviceCount() {
  var deviceCount = document.querySelectorAll('.devicedetails').length;
  document.getElementById('deviceCount').textContent = deviceCount;
}

// Function to update the total number of devices when the page loads
window.addEventListener('load', function () {
  uncheckDeviceCheckboxes();
  updateDeviceCount();
});

document.getElementById('deviceForm').addEventListener('submit', function(event) {
  var existingRoomSelect = document.getElementById('existingRoom');
  var selectedOption = existingRoomSelect.options[existingRoomSelect.selectedIndex];
  if (selectedOption.value === "" && selectedOption.text === "No rooms found") {
      alert("No rooms found. Please create a room in the Rooms page.");
      event.preventDefault(); // Prevent form submission
  }
});


// Function to fetch device details from the database and display them
function fetchDeviceDetails() {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var devices = JSON.parse(xhr.responseText);
        displayDevices(devices);
      } else {
        console.error('Error fetching device data: ' + xhr.status);
      }
    }
  };

  // Construct the URL for fetching device data
  var url = 'fetch_device_details.php';
  xhr.open('GET', url, true);
  xhr.send();
}

function displayDevices(devices) {
  var tabContainer = document.getElementById('tab');
  if (tabContainer) {
    tabContainer.innerHTML = ''; // Clear existing content
    devices.forEach(function (device) {
      var deviceDetailsHTML = "<div class='devicedetails' data-device-id='" + device.DeviceId + "' data-device-type='" + device.DeviceType + "'>";
      // Add hidden input field to store DeviceId
      deviceDetailsHTML += "<input type='hidden' class='deviceId' value='" + device.DeviceId + "'>";
      // Add checkbox and image based on device type
      deviceDetailsHTML += "<input type='checkbox' class='deviceCheckbox' name='selectedDevices[]''>" +
        "<img class='icontype' src='" + getDeviceImage(device.DeviceType) + "' >";
      deviceDetailsHTML += "<p id='dev'>" + device.DeviceName + "</p></div>";
      tabContainer.insertAdjacentHTML('beforeend', deviceDetailsHTML);
    });
    updateDeviceCount(); // Update device count
  } else {
    console.error("tab div not found");
  }
}

// Function to get device image based on device type
function getDeviceImage(deviceType) {
  // Add logic to return image URL based on device type
  switch (deviceType) {
    case 'Lights':
      return 'bulb.png';
    case 'Doors':
      return 'door.png';
    case 'Fans':
      return 'fan.png';
    case 'Thermostat':
      return 'thermostat.png';
    case 'Ac':
      return 'ac.png';
    case 'Plugs':
      return 'plug.png';
    default:
      return ''; // Add default image URL or handle other cases
  }
}

// Fetch device details when the page is loaded
fetchDeviceDetails();

// Function to filter and rearrange device details based on selected device type
document.getElementById('devices').addEventListener('change', function () {
  var selectedDeviceType = this.value; // Get the selected device type
  var deviceDetailsDivs = document.querySelectorAll('.devicedetails'); // Get all device details divs

  // Loop through all device details divs and update visibility based on selected device type
  deviceDetailsDivs.forEach(function (div) {
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

var AllCheckbox = document.getElementById('selectall');
if (AllCheckbox) {
    AllCheckbox.addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('.deviceCheckbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = AllCheckbox.checked;
        });
    });
}
document.getElementById('all').style.display = 'none';
var selectAllCheckbox = document.getElementById('select');
if (selectAllCheckbox) {
  selectAllCheckbox.addEventListener('change', function () {
    if (selectAllCheckbox.checked) {
      document.getElementById('selectall').style.display = 'inline-block';
      document.getElementById('all').style.display = 'inline';
  } else {
    document.getElementById('selectall').style.display = 'none';
    document.getElementById('all').style.display = 'none';
  }
    var checkboxes = document.querySelectorAll('.deviceCheckbox');
    checkboxes.forEach(function (checkbox) {
      checkbox.style.visibility = selectAllCheckbox.checked ? 'visible' : 'hidden';
    });
  });
}

function removeSelectedDevices() {
  var selectedDeviceIds = [];
  var checkboxes = document.querySelectorAll('.deviceCheckbox:checked');
  checkboxes.forEach(function(checkbox) {
      // Traverse up the DOM tree to find the parent div with 'devicedetails' class
      var parentDiv = checkbox.closest('.devicedetails');
      if (parentDiv) {
          // Get the device id from the data-device-id attribute of the parent div
          var deviceId = parentDiv.dataset.deviceId;
          selectedDeviceIds.push(deviceId);
          console.log("Selected DeviceId:", deviceId); // Log the deviceId to the console
      }
  });

  if (selectedDeviceIds.length > 0) {
      // Confirm deletion with user
      var confirmation = confirm("Are you sure you want to delete the selected device(s)?");
      
      if (confirmation) {
          var xhr = new XMLHttpRequest();
          xhr.open("POST", "remove_device.php", true);
          xhr.setRequestHeader("Content-Type", "application/json");
          xhr.onreadystatechange = function() {
              if (xhr.readyState === XMLHttpRequest.DONE) {
                  if (xhr.status === 200) {
                      var response = JSON.parse(xhr.responseText);
                      if (response === "success") {
                          // Clear existing device list
                          var tab = document.getElementById("tab");
                          tab.innerHTML = ""; // Clear all child elements

                          // Refresh device list after successful deletion
                          updateDeviceCount();
                          fetchDeviceDetails();
                          uncheckDeviceCheckboxes();
                      } else {
                          console.error("Failed to remove devices.");
                          // Optionally, display an error message to the user
                      }
                  } else {
                      console.error("Failed to remove devices:", xhr.status);
                      // Optionally, display an error message to the user
                  }
              }
          };
          xhr.send(JSON.stringify(selectedDeviceIds));
      }
  } else {
      alert("Please select at least one device to remove.");
  }
}

// Function to uncheck all device checkboxes on page reload
function uncheckDeviceCheckboxes() {
  document.getElementById('select').checked = false;
  document.getElementById('selectall').style.display = 'none';
  document.getElementById('all').style.display = 'none';
  var checkboxes = document.querySelectorAll('.deviceCheckbox');
  checkboxes.forEach(function(checkbox) {
    checkbox.checked = false;
  });
}
