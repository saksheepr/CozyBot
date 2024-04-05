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
document.getElementById('devices').addEventListener('change', function () {
    var deviceSelect = document.getElementById('RoomName');
    deviceSelect.value = "";
});

// Function to fetch devices based on selected room
document.getElementById('RoomName').addEventListener('change', function () {
    var selectedRoom = this.value;
    var selectedDeviceType = document.getElementById('devices').value; // Get selected device type
    console.log("Room : ", selectedRoom);
    console.log("DeviceType: ", selectedDeviceType);
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var devices = JSON.parse(this.responseText);
            var deviceSelect = document.getElementById('DeviceName');
            deviceSelect.innerHTML = ''; // Clear previous options

            if (devices.length === 0) {
                var option = document.createElement('option');
                option.value = "";
                option.textContent = "No. devices Found";
                deviceSelect.appendChild(option);
            } else {
                var option = document.createElement('option');
                option.value = "";
                option.textContent = "Choose a Device";
                deviceSelect.appendChild(option);
                devices.forEach(function (device) {
                    var option = document.createElement('option');
                    option.value = device.DeviceName;
                    option.textContent = device.DeviceName;
                    deviceSelect.appendChild(option);
                });
            }
        }
    };
    xhr.open('GET', 'get_devices.php?room=' + selectedRoom + '&deviceType=' + selectedDeviceType, true);
    xhr.send();
});

document.getElementById('DeviceName').addEventListener('change', function () {
    var selectedDevice = this.value;
    console.log("Device is ", selectedDevice);
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var settings = JSON.parse(this.responseText);
            var settingSelect = document.getElementById('SettingsName');
            settingSelect.innerHTML = ''; // Clear previous options
            console.log("Settings ", settings);

            if (settings.length === 0) {
                var option = document.createElement('option');
                option.value = "";
                option.textContent = "No. Settings Found";
                settingSelect.appendChild(option);
            } else {
                var option = document.createElement('option');
                option.value = "";
                option.textContent = "Choose a Setting";
                settingSelect.appendChild(option);
                var option = document.createElement('option');
                option.value = "deviceStatus";
                option.textContent = "Device Status";
                settingSelect.appendChild(option);
                settings.forEach(function (setting) {
                    var option = document.createElement('option');
                    option.value = setting.SettingName;
                    option.textContent = setting.SettingName;
                    settingSelect.appendChild(option);
                });
            }
        }
    };
    xhr.open('GET', 'get_settings.php?device=' + selectedDevice, true);
    xhr.send();

});

document.getElementById('SettingsName').addEventListener('change', function () {
    var selectedSettings = this.value;
    console.log("devv",selectedSettings);
    var selectedDeviceType = document.getElementById('devices').value;
    console.log("devv",selectedDeviceType);
    var settingSelect = document.getElementById('SettingsValue');
    if (selectedDeviceType == "Lights") {
        if (selectedSettings === "deviceStatus") {
            var option = document.createElement('option');
            option.value = "off";
            option.textContent = "OFF";
            settingSelect.appendChild(option);
            var option = document.createElement('option');
            option.value = "on";
            option.textContent = "ON";
            settingSelect.appendChild(option);

        }
    }

});