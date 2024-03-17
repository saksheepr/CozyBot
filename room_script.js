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

document.addEventListener('DOMContentLoaded', function() {
    fetchRooms();
});

function fetchRooms() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_rooms.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var rooms = JSON.parse(xhr.responseText);
            displayRooms(rooms);
        }
    };
    xhr.send();
}

function displayRooms(rooms) {
    var tab = document.getElementById("tab");
    rooms.forEach(function(room, index) { // Change the parameter name to room to represent the entire room object
        var newRoom = document.createElement("div");
        newRoom.className = "room-container";
        
        var roomId = room.RoomID; // Get room ID

        // Create hidden input field to store room ID
        var roomIdInput = document.createElement("input");
        roomIdInput.type = "hidden";
        roomIdInput.value = roomId;
        newRoom.appendChild(roomIdInput);

        var checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.className = "roomCheckbox";
        checkbox.value = roomId; // Set checkbox value to room ID
        newRoom.appendChild(checkbox);

        checkbox.addEventListener('change', function() {
            if (checkbox.checked) {
                // If checkbox is checked, log the room ID
                console.log("Room ID:", roomIdInput.value);
            }
        });
        
        var img = document.createElement("img");
        img.src = "room2.jpg";
        img.className = "room-image";
        var text = document.createElement("p");
        text.textContent = room.RoomName; // Access room name from the room object
        text.className = "room-text";
        newRoom.appendChild(img);
        newRoom.appendChild(text);
        tab.appendChild(newRoom);
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
        var checkboxes = document.querySelectorAll('.roomCheckbox');
        checkboxes.forEach(function (checkbox) {
          checkbox.style.visibility = selectAllCheckbox.checked ? 'visible' : 'hidden';
        });
      });
    }
var AllCheckbox = document.getElementById('selectall');
if (AllCheckbox) {
    AllCheckbox.addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('.roomCheckbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = AllCheckbox.checked;
        });
    });
}

function removeSelectedRooms() {
    var selectedRoomIds = [];
    var checkboxes = document.querySelectorAll('.roomCheckbox:checked');
    checkboxes.forEach(function(checkbox) {
        selectedRoomIds.push(checkbox.value);
    });

    if (selectedRoomIds.length > 0) {
        // Confirm deletion with user
        var confirmation = confirm("Are you sure you want to delete the selected room(s)? This will also remove all connected devices.");
        
        if (confirmation) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "remove_room.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response === "success") {
                            // Clear existing room list
                            var tab = document.getElementById("tab");
                            tab.innerHTML = ""; // Clear all child elements

                            // Refresh room list after successful deletion
                            fetchRooms();
                        } else {
                            console.error("Failed to remove rooms.");
                            // Optionally, display an error message to the user
                        }
                    } else {
                        console.error("Failed to remove rooms:", xhr.status);
                        // Optionally, display an error message to the user
                    }
                }
            };
            xhr.send(JSON.stringify(selectedRoomIds));
        }
    } else {
        alert("Please select at least one room to remove.");
    }
}
