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
    var roomOption = document.querySelector('input[name="roomOption"]:checked').value;
    var roomName = '';
    
    // Determine room name based on the user's choice
    if (roomOption === 'Existing') {
      roomName = document.getElementById('existingRooms').value;
    } else {
      roomName = document.getElementById('newRoomName').value;
    }


    // Close the popup
    closePopup();
  });
