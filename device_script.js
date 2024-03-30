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

document.getElementById('deviceForm').addEventListener('submit', function (event) {
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
    case 'Geyser':
      return 'geyser.png';
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
  AllCheckbox.addEventListener('change', function () {
    var checkboxes = document.querySelectorAll('.deviceCheckbox');
    checkboxes.forEach(function (checkbox) {
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
  checkboxes.forEach(function (checkbox) {
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
      xhr.onreadystatechange = function () {
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
  checkboxes.forEach(function (checkbox) {
    checkbox.checked = false;
  });
}

// Add event listener to the parent element
document.getElementById('tab').addEventListener('click', function (event) {
  // Check if the click occurred on a devicedetails element
  if (event.target.classList.contains('devicedetails')) {
    // Call the function when a devicedetails element is clicked
    DeviceDetailsClick(event.target);
  }
});
// Function to handle click on devicedetails
function DeviceDetailsClick(deviceDetails) {
  document.getElementById('pic').style.display = 'none';
  document.getElementById('set').style.display = 'block';

  var deviceId = deviceDetails.getAttribute('data-device-id');
  console.log('Device ID clicked:', deviceId);

  // Make an AJAX request to fetch device details
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      var deviceDetails = JSON.parse(this.responseText);
      // Update device name
      document.getElementById('t1').textContent = deviceDetails.DeviceName;
      // Update device status (assuming the checkbox is for status)
      document.querySelector('.switch input').checked = deviceDetails.DeviceStatus === 'On';
      // Update room name
      document.getElementById('roomName').textContent = 'Room Name : ' + deviceDetails.RoomName;

      var deviceType = deviceDetails.DeviceType;
      console.log('Device Type ', deviceType);
      if (deviceType == 'Lights') {
        document.getElementById('slidecontainer').style.display = 'block';
        document.getElementById('type1').style.display = 'block';
        document.getElementById('type2').style.display = 'none';
        document.getElementById('type3').style.display = 'none';
        document.getElementById('type4').style.display = 'none';
        document.getElementById('type5').style.display = 'none';
        document.getElementById('type6').style.display = 'none';
        document.getElementById('mode').style.display = 'block';
        document.getElementById('bulb-shade').style.display = 'block';
        document.getElementById('speed-fan').style.display = 'none';
        document.getElementById('settings').style.display = 'none';
        document.getElementById('slidecontainer2').style.display = 'none';
        document.getElementById('thermo-mode').style.display = 'none';
        document.getElementById('fan-control').style.display = 'none';
        fetchAndSetSettings(deviceId);
      }
      else if (deviceType == 'Fans') {
        document.getElementById('slidecontainer').style.display = 'none';
        document.getElementById('type2').style.display = 'block';
        document.getElementById('type1').style.display = 'none';
        document.getElementById('type3').style.display = 'none';
        document.getElementById('type4').style.display = 'none';
        document.getElementById('type5').style.display = 'none';
        document.getElementById('type6').style.display = 'none';
        document.getElementById('mode').style.display = 'block';
        document.getElementById('bulb-shade').style.display = 'none';
        document.getElementById('speed-fan').style.display = 'block';
        document.getElementById('settings').style.display = 'block';
        document.getElementById('slidecontainer2').style.display = 'none';
        document.getElementById('thermo-mode').style.display = 'none';
        document.getElementById('fan-control').style.display = 'none';
        fetchAndSetSettings(deviceId);
      }
      else if (deviceType == 'Thermostat') {
        document.getElementById('slidecontainer2').style.display = 'block';
        document.getElementById('slidecontainer').style.display = 'none';
        document.getElementById('type3').style.display = 'block';
        document.getElementById('type2').style.display = 'none';
        document.getElementById('type1').style.display = 'none';
        document.getElementById('type4').style.display = 'none';
        document.getElementById('type5').style.display = 'none';
        document.getElementById('type6').style.display = 'none';
        document.getElementById('mode').style.display = 'none';
        document.getElementById('bulb-shade').style.display = 'none';
        document.getElementById('speed-fan').style.display = 'none';
        document.getElementById('settings').style.display = 'none';
        document.getElementById('thermo-mode').style.display = 'block';
        document.getElementById('fan-control').style.display = 'block';
        fetchAndSetSettings(deviceId);
      }
      else if (deviceType == 'Ac') {
        document.getElementById('slidecontainer').style.display = 'none';
        document.getElementById('type3').style.display = 'none';
        document.getElementById('type2').style.display = 'none';
        document.getElementById('type1').style.display = 'none';
        document.getElementById('type4').style.display = 'block';
        document.getElementById('type5').style.display = 'none';
        document.getElementById('type6').style.display = 'none';
        document.getElementById('mode').style.display = 'none';
        document.getElementById('bulb-shade').style.display = 'none';
        document.getElementById('speed-fan').style.display = 'none';
        document.getElementById('settings').style.display = 'none';
        document.getElementById('slidecontainer2').style.display = 'none';
        document.getElementById('thermo-mode').style.display = 'none';
        document.getElementById('fan-control').style.display = 'none';
      }
      else if (deviceType == 'Geyser') {
        document.getElementById('slidecontainer').style.display = 'none';
        document.getElementById('type3').style.display = 'none';
        document.getElementById('type2').style.display = 'none';
        document.getElementById('type1').style.display = 'none';
        document.getElementById('type4').style.display = 'none';
        document.getElementById('type5').style.display = 'block';
        document.getElementById('type6').style.display = 'none';
        document.getElementById('mode').style.display = 'none';
        document.getElementById('bulb-shade').style.display = 'none';
        document.getElementById('speed-fan').style.display = 'none';
        document.getElementById('settings').style.display = 'none';
        document.getElementById('slidecontainer2').style.display = 'none';
        document.getElementById('thermo-mode').style.display = 'none';
        document.getElementById('fan-control').style.display = 'none';
      }
      else {
        document.getElementById('slidecontainer').style.display = 'none';
        document.getElementById('type1').style.display = 'none';
        document.getElementById('type2').style.display = 'none';
        document.getElementById('type3').style.display = 'none';
        document.getElementById('type4').style.display = 'none';
        document.getElementById('type5').style.display = 'none';
        document.getElementById('type6').style.display = 'block';
        document.getElementById('mode').style.display = 'none';
        document.getElementById('bulb-shade').style.display = 'none';
        document.getElementById('speed-fan').style.display = 'none';
        document.getElementById('settings').style.display = 'none';
        document.getElementById('slidecontainer2').style.display = 'none';
        document.getElementById('thermo-mode').style.display = 'none';
        document.getElementById('fan-control').style.display = 'none';
      }

    }

  };

  xhr.open("GET", "get_device_details.php?deviceId=" + deviceId, true);
  xhr.send();
}

// Function to close the popup
function closeDeviceSettings() {
  document.getElementById('pic').style.display = 'block';
  document.getElementById('set').style.display = 'none';
}

const bulbs = document.querySelectorAll('.bulb');

// Add click event listener to each bulb
bulbs.forEach(bulb => {
  bulb.addEventListener('click', () => {
    // Remove 'selected' class from all bulbs
    bulbs.forEach(b => b.classList.remove('selected'));
    // Add 'selected' class to the clicked bulb
    bulb.classList.add('selected');
  });
});

const modes = document.querySelectorAll('.mode');

// Add click event listener to each mode
modes.forEach(mode => {
  mode.addEventListener('click', () => {
    // Remove 'selected' class from all modes
    modes.forEach(m => m.classList.remove('selected-mode'));
    // Add 'selected' class to the clicked mode
    mode.classList.add('selected-mode');
  });
});

const circles = document.querySelectorAll('.speed-circle');

// Add click event listener to each speed circle
circles.forEach(circle => {
  circle.addEventListener('click', () => {
    // Remove 'selected-speed' class from all circles
    circles.forEach(c => c.classList.remove('selected-speed'));
    // Add 'selected-speed' class to the clicked circle
    circle.classList.add('selected-speed');
  });
});

const mode = document.querySelectorAll('.mo');

// Add click event listener to each mode
mode.forEach(mo => {
  mo.addEventListener('click', () => {
    // Remove 'selected' class from all mode
    mode.forEach(m => m.classList.remove('selected-modes'));
    // Add 'selected' class to the clicked modes
    mo.classList.add('selected-modes');
  });
});

function fetchAndSetSettings(deviceId) {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      var settings = JSON.parse(this.responseText);
      console.log('Settings:', settings);

      var deviceType = settings.DeviceType;
      console.log('Device Type:', deviceType);

      if (deviceType === 'Lights') {
        // Update brightness slider value and output
        var brightnessSlider = document.getElementById('myRange');
        var brightnessOutput = document.getElementById('demo');
        brightnessOutput.innerHTML = settings.Brightness;
        brightnessSlider.value = parseInt(settings.Brightness);
        // Update slider color
        updateSliderColor(brightnessSlider);

        // Update mode
        var selectedModeId = settings.Mode;
        if (selectedModeId) {
          modes.forEach(m => {
            if (m.id === selectedModeId) {
              m.classList.add('selected-mode'); // Add 'selected-mode' class to the selected mode
            } else {
              m.classList.remove('selected-mode'); // Remove 'selected-mode' class from other modes
            }
          });
        }

        // Update shade
        var selectedShadeId = settings.Shade;
        if (selectedShadeId) {
          bulbs.forEach(b => {
            if (b.id === selectedShadeId) {
              b.classList.add('selected');
            } else {
              b.classList.remove('selected');
            }
          });
        }
      }
      else if (deviceType === 'Fans') {
        var selectedSpeedId = settings.Fan_Speed;
        if (selectedSpeedId) {
          updateFanSpeed(selectedSpeedId);
          // Function to update fan speed
          function updateFanSpeed(selectedSpeedId) {
            // Remove 'selected-speed' class from all circles
            var circles = document.querySelectorAll('.speed-circle');
            circles.forEach(function (circle) {
              circle.classList.remove('selected-speed');
            });

            // Add 'selected-speed' class to the clicked circle
            var selectedSpeedElement = document.getElementById(selectedSpeedId);
            if (selectedSpeedElement) {
              selectedSpeedElement.classList.add('selected-speed');
            }
          }
        }

        // Update mode
        var selectedModeId = settings.Mode;
        if (selectedModeId) {
          modes.forEach(m => {
            if (m.id === selectedModeId) {
              m.classList.add('selected-mode'); // Add 'selected-mode' class to the selected mode
            } else {
              m.classList.remove('selected-mode'); // Remove 'selected-mode' class from other modes
            }
          });
        }

        // Update direction
        var selectedDirection = settings.Direction;
        if (selectedDirection) {
          var directionSelect = document.getElementById('direction');
          if (directionSelect) {
            for (var i = 0; i < directionSelect.options.length; i++) {
              if (directionSelect.options[i].value === selectedDirection) {
                directionSelect.selectedIndex = i;
                break;
              }
            }
          }
        }
      } else if (deviceType === 'Thermostat') {
        console.log("Temperature:", settings.Temperature);
        // Update temperature slider value and output
        var tempSlider = document.getElementById('myRange2');
        var tempOutput = document.getElementById('demo2');
        tempOutput.innerHTML = settings.Temperature + '°F';
        tempSlider.value = parseInt(settings.Temperature);
        updateSliderColor(tempSlider);

        console.log("Mode:", settings.Mode);
        // Update mode
        var selectedModeId = settings.Mode;
        if (selectedModeId) {
          mode.forEach(m => {
            if (m.id === selectedModeId) {
              m.classList.add('selected-modes'); // Add 'selected-mode' class to the selected mode
            } else {
              m.classList.remove('selected-modes'); // Remove 'selected-mode' class from other modes
            }
          });
        }
        console.log("Fan Control : ", settings.FanControl);
        // Update fan control
        var fanControlCheckbox = document.getElementById('fan-con');
        if (fanControlCheckbox) {
          // Check the checkbox if fan control is 'On', otherwise leave it unchecked
          fanControlCheckbox.querySelector('input[type="checkbox"]').checked = settings.FanControl === 'On';
        }
      }
    }
  };

  xhr.open("GET", "get_light_settings.php?deviceId=" + deviceId, true);
  xhr.send();
}

// Initialize brightness slider
var brightnessSlider = document.getElementById("myRange");
var brightnessOutput = document.getElementById("demo");
brightnessOutput.innerHTML = brightnessSlider.value;

brightnessSlider.oninput = function () {
  brightnessOutput.innerHTML = this.value;
  updateSliderColor(brightnessSlider);
}

// Initialize temperature slider
var tempSlider = document.getElementById("myRange2");
var tempOutput = document.getElementById("demo2");
tempOutput.innerHTML = tempSlider.value + '°F';

tempSlider.oninput = function () {
  tempOutput.innerHTML = this.value + '°F';
  updateSliderColor(tempSlider);
}

// Update the slider color on page load
updateSliderColor(brightnessSlider);
updateSliderColor(tempSlider);

function updateSliderColor(slider) {
  var value = slider.value;
  var thumbWidth = 20; // Adjust this value based on your thumb width
  var trackWidth = slider.offsetWidth - thumbWidth;
  var gradientStop = (value - slider.min) / (slider.max - slider.min); // Calculate the percentage of the slider filled
  var trackColor = "linear-gradient(to right, #1D084B " + (gradientStop * trackWidth) + "px, #d3d3d3 " + (gradientStop * trackWidth) + "px)";
  slider.style.background = trackColor;
}
