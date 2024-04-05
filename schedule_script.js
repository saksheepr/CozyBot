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

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('SettingsName').addEventListener('change', function () {
        var selectedSettings = this.value;
        var selectedDeviceType = document.getElementById('devices').value;

        function createOption(value, text) {
            var option = document.createElement('option');
            option.value = value;
            option.textContent = text;
            return option;
        }

        function createSelect(options) {
            var select = document.createElement('select');
            options.forEach(function (option) {
                var opt = document.createElement('option');
                opt.value = option.value;
                opt.textContent = option.text;
                select.appendChild(opt);
            });
            return select;
        }

        // Clear previous input field or select element
        var settingsValueSelect = document.getElementById('SettingsValue');
        settingsValueSelect.innerHTML = '';

        if (selectedSettings === "deviceStatus") {
            var optionOff = document.createElement('option');
            optionOff.value = "off";
            optionOff.textContent = "OFF";

            var optionOn = document.createElement('option');
            optionOn.value = "on";
            optionOn.textContent = "ON";

            settingsValueSelect.appendChild(optionOff);
            settingsValueSelect.appendChild(optionOn);
        } else if (selectedDeviceType === "Lights") {
            if (selectedSettings === "Brightness") {
                var inputBrightness = document.createElement('input');
                inputBrightness.className = "input";
                inputBrightness.type = "number";
                inputBrightness.min = "0";
                inputBrightness.max = "100";
                inputBrightness.step = "1";
                inputBrightness.placeholder = "Enter brightness (0-100)";
                inputBrightness.id = "SettingsValue";
                inputBrightness.name = "SettingsValue";
                settingsValueSelect.parentNode.replaceChild(inputBrightness, settingsValueSelect);
            } else if (selectedSettings === "Mode") {
                var options = [
                    createOption("morning", "Morning"),
                    createOption("day", "Day"),
                    createOption("night", "Night"),
                    createOption("power_saving", "Power Saving")
                ];

                var settingSelect = createSelect(options);
                settingSelect.id = "SettingsValue";
                settingSelect.name = "SettingsValue";
                settingsValueSelect.parentNode.replaceChild(settingSelect, settingsValueSelect);
            } else if (selectedSettings === "Shade") {
                var options = [
                    createOption("white", "White"),
                    createOption("cream", "Cream"),
                    createOption("red", "Red"),
                    createOption("green", "Green"),
                    createOption("blue", "Blue"),
                    createOption("yellow", "Yellow"),
                    createOption("orange", "Orange"),
                    createOption("purple", "Purple"),
                    createOption("pink", "Pink"),
                    createOption("cyan", "Cyan"),
                    createOption("indigo", "Indigo"),
                    createOption("lime", "Lime")
                ];

                var settingSelect = createSelect(options);
                settingSelect.id = "SettingsValue";
                settingSelect.name = "SettingsValue";
                settingsValueSelect.parentNode.replaceChild(settingSelect, settingsValueSelect);
            }
        } else if (selectedDeviceType === "Doors") {
            if (selectedSettings === "Lock Status" || selectedSettings === "Mode" || selectedSettings === "Locking Preference") {
                var options = (selectedSettings === "Lock Status") ?
                    [createOption("locked", "Locked"), createOption("unlocked", "Unlocked")] :
                    (selectedSettings === "Mode") ?
                        [createOption("stay", "Home"), createOption("away", "Away")] :
                        [createOption("manual", "Manual Lock"), createOption("automatic", "Automatic Lock")];

                var settingSelect = createSelect(options);
                settingSelect.id = "SettingsValue";
                settingSelect.name = "SettingsValue";
                settingsValueSelect.parentNode.replaceChild(settingSelect, settingsValueSelect);
            }
        } else if (selectedDeviceType === "Fans") {
            if (selectedSettings === "Fan_Speed" || selectedSettings === "Mode" || selectedSettings === "Direction") {
                var options = (selectedSettings === "Fan_Speed") ?
                    [createOption("1", "1"), createOption("2", "2"), createOption("3", "3"), createOption("4", "4"), createOption("5", "5")] :
                    (selectedSettings === "Mode") ?
                        [createOption("morning", "Morning"), createOption("day", "Day"), createOption("night", "Night"), createOption("power_saving", "Power Saving")] :
                        [createOption("clockwise", "Clockwise"), createOption("counterclockwise", "Counter Clockwise")];

                var settingSelect = createSelect(options);
                settingSelect.id = "SettingsValue";
                settingSelect.name = "SettingsValue";
                settingsValueSelect.parentNode.replaceChild(settingSelect, settingsValueSelect);
            }
        } else if (selectedDeviceType === "Thermostat") {
            if (selectedSettings === "Temperature" || selectedSettings === "Mode" || selectedSettings === "FanControl") {
                var inputTemperature = (selectedSettings === "Temperature") ?
                    document.createElement('input') :
                    null;

                if (inputTemperature) {
                    inputTemperature.className = "input";
                    inputTemperature.type = "number";
                    inputTemperature.min = "40";
                    inputTemperature.max = "90";
                    inputTemperature.step = "1";
                    inputTemperature.placeholder = "Enter temperature (40-90)";
                    inputTemperature.id = "SettingsValue";
                    inputTemperature.name = "SettingsValue";
                    settingsValueSelect.parentNode.replaceChild(inputTemperature, settingsValueSelect);
                } else {
                    var options = (selectedSettings === "Mode") ?
                        [createOption("Heating", "Heating"), createOption("Cooling", "Cooling"), createOption("Auto", "Auto")] :
                        [createOption("on", "On"), createOption("off", "Off")];

                    var settingSelect = createSelect(options);
                    settingSelect.id = "SettingsValue";
                    settingSelect.name = "SettingsValue";
                    settingsValueSelect.parentNode.replaceChild(settingSelect, settingsValueSelect);
                }
            }
        } else if (selectedDeviceType === "Ac") {
            if (selectedSettings === "Temperature" || selectedSettings === "Mode") {
                var inputTemperature = (selectedSettings === "Temperature") ?
                    document.createElement('input') :
                    null;

                if (inputTemperature) {
                    inputTemperature.className = "input";
                    inputTemperature.type = "number";
                    inputTemperature.min = "15";
                    inputTemperature.max = "30";
                    inputTemperature.step = "1";
                    inputTemperature.placeholder = "Enter temperature (15-30)";
                    inputTemperature.id = "SettingsValue";
                    inputTemperature.name = "SettingsValue";
                    settingsValueSelect.parentNode.replaceChild(inputTemperature, settingsValueSelect);
                } else {
                    var options = [
                        createOption("cool", "Cool"), createOption("fan", "Fan"), createOption("automode", "Auto"),
                        createOption("dry", "Dry"), createOption("eco", "Eco"), createOption("turbo", "Turbo"),
                        createOption("heat", "Heat")
                    ];

                    var settingSelect = createSelect(options);
                    settingSelect.id = "SettingsValue";
                    settingSelect.name = "SettingsValue";
                    settingsValueSelect.parentNode.replaceChild(settingSelect, settingsValueSelect);
                }
            }
        } else if (selectedDeviceType === "Geyser") {
            if (selectedSettings === "Temperature" || selectedSettings === "Mode") {
                var inputTemperature = (selectedSettings === "Temperature") ?
                    document.createElement('input') :
                    null;

                if (inputTemperature) {
                    inputTemperature.className = "input";
                    inputTemperature.type = "number";
                    inputTemperature.min = "30";
                    inputTemperature.max = "60";
                    inputTemperature.step = "1";
                    inputTemperature.placeholder = "Enter temperature (30-60)";
                    inputTemperature.id = "SettingsValue";
                    inputTemperature.name = "SettingsValue";
                    settingsValueSelect.parentNode.replaceChild(inputTemperature, settingsValueSelect);
                } else {
                    var options = [
                        createOption("morning", "Morning"), createOption("day", "Day"), createOption("night", "Night"),
                        createOption("power_saving", "Power Saving")
                    ];

                    var settingSelect = createSelect(options);
                    settingSelect.id = "SettingsValue";
                    settingSelect.name = "SettingsValue";
                    settingsValueSelect.parentNode.replaceChild(settingSelect, settingsValueSelect);
                }
            }
        }

        document.getElementById('SettingsValue').addEventListener('change', function () {
            var settingsValue = this.value;
            console.log("SettingsValue: ", settingsValue);
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Attach event listener after the DOM has loaded
    document.getElementById('SettingsValue').addEventListener('change', function () {
        // Retrieve the selected value when the change event occurs
        var settingsValue = this.value;
        console.log("SettingsValue: ", settingsValue);
    });
});

var days = document.querySelectorAll('.day');

// Add event listener to each day element
days.forEach(function (day) {
    day.addEventListener('click', function () {
        // Toggle the 'selected' class
        this.classList.toggle('selected');
    });
});

fetchScheduleDetails();

function fetchScheduleDetails() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var schedules = JSON.parse(xhr.responseText);
                displaySchedules(schedules);
            } else {
                console.error('Error fetching device data: ' + xhr.status);
            }
        }
    };

    // Construct the URL for fetching device data
    var url = 'fetch_schedule_details.php';
    xhr.open('GET', url, true);
    xhr.send();
}

// Define the updateScheduleStatus function outside of displaySchedules
function updateScheduleStatus(scheduleID, newStatus) {
    // Create new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Prepare and send POST request to update_schedule_status.php
    xhr.open('POST', 'update_schedule_status.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    console.log('Schedule status updated successfully');
                } else {
                    console.error('Failed to update schedule status:', response.message);
                }
            } else {
                console.error('Error updating schedule status:', xhr.status);
            }
        }
    };
    var requestData = JSON.stringify({ scheduleID: scheduleID, newStatus: newStatus });
    xhr.send(requestData);
}

// Define displaySchedules function
function displaySchedules(schedules) {
    console.log("Schedule :", schedules);
    var tabContainer = document.getElementById('existingschedules');
    if (tabContainer) {
        tabContainer.innerHTML = ''; // Clear existing content
        schedules.forEach(function (schedule) {
            var scheduleDiv = document.createElement('div');
            scheduleDiv.classList.add('schedule');
            scheduleDiv.setAttribute('schedule-id', schedule.ScheduleID);

            // Create div for schedule name and toggle switch
            var nameDiv = document.createElement('div');
            nameDiv.id = 'name';
            var scheduleName = document.createElement('p');
            scheduleName.textContent = schedule.ScheduleName;
            var toggleSwitch = document.createElement('label');
            toggleSwitch.classList.add('switch');
            var toggleInput = document.createElement('input');
            toggleInput.type = 'checkbox';

            // Set the checked attribute based on the schedule status
            toggleInput.checked = (schedule.Status === 'On');

            // Add event listener to the toggleInput
            toggleInput.addEventListener('change', function () {
                // Get the schedule ID from the parent element's attribute
                var scheduleID = scheduleDiv.getAttribute('schedule-id');

                // Get the new status based on the toggleInput's checked state
                var newStatus = toggleInput.checked ? 'On' : 'Off';
                // Call the updateScheduleStatus function
                updateScheduleStatus(scheduleID, newStatus);
            });

            var toggleSpan = document.createElement('span');
            toggleSpan.classList.add('slider', 'round');

            // Append elements to nameDiv
            toggleSwitch.appendChild(toggleInput);
            toggleSwitch.appendChild(toggleSpan);
            nameDiv.appendChild(scheduleName);
            nameDiv.appendChild(toggleSwitch);

            // Create img element for icon
            var iconImg = document.createElement('img');
            iconImg.classList.add('icons');
            iconImg.src = 'sch1.png'; // Assuming the icon source is available in the JSON or predefined

            // Append nameDiv and iconImg to scheduleDiv
            scheduleDiv.appendChild(nameDiv);
            scheduleDiv.appendChild(iconImg);

            // Append scheduleDiv to tabContainer
            tabContainer.appendChild(scheduleDiv);
        });
    } else {
        console.error("tab div not found");
    }
}
