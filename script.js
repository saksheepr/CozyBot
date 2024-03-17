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

document.getElementById('all').style.display = 'none';

// Function to update the total number of devices
function updateDeviceCount() {
  var deviceCount = document.querySelectorAll('.devicedetails').length;
  document.getElementById('deviceCount').textContent = deviceCount;
}

// Function to update the total number of devices when the page loads
window.addEventListener('load', function () {
  updateDeviceCount();
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

// Function to display devices in the tab div
function displayDevices(devices) {
  var tabContainer = document.getElementById('tab');
  if (tabContainer) {
    tabContainer.innerHTML = ''; // Clear existing content
    devices.forEach(function (device) {
      var deviceDetailsHTML = "<div class='devicedetails' data-device-type='" + device.DeviceType + "'>";
      // Add checkbox and image based on device type
      deviceDetailsHTML += "<input type='checkbox' class='deviceCheckbox' name='selectedDevices[]' style='visibility:hidden;'>" +
        "<img class='icontype' src='" + getDeviceImage(device.DeviceType) + "' >";
      deviceDetailsHTML += "<p id='dev'>" + device.DeviceName + "</p></div>";
      tabContainer.insertAdjacentHTML('beforeend', deviceDetailsHTML);
    });
    updateDeviceCount(); // Update device count
    // Add event listener to the select all checkbox
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
  } else {
    console.error("tab div not found");
  }
}
var selectAllCheckbox = document.getElementById('selectall');
if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('.deviceCheckbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
        });
    });
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