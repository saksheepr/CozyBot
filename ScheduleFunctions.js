function openNav(){
  document.getElementById("nav_shrink").style = "visibility: visible";
  document.getElementById("nav_expand").style = "visibility: hidden";
  document.getElementById("content").style.left = "70px";    
}
function closeNav(){
  document.getElementById("nav_shrink").style = "visibility: hidden";
  document.getElementById("nav_expand").style = "visibility: visible";
  document.getElementById("content").style.left="220px";
}
function adjustContentWidth() {
  var navWidth = document.getElementById("nav").offsetWidth;
  var windowWidth = window.innerWidth;
  var contentWidth = windowWidth - navWidth;
  document.getElementById("content").style.width = contentWidth + "px";
}
document.addEventListener("DOMContentLoaded", function () {
  var addButton = document.getElementById("add-schedule-btn");

  addButton.addEventListener("click", function () {
      var deviceName = document.getElementById("device-name").value;
      var startTime = document.getElementById("start-time").value;
      var endTime = document.getElementById("end-time").value;
      
      // Create HTML structure for the schedule
      var scheduleItem = document.createElement("div");
      scheduleItem.classList.add("schedule-item");
      scheduleItem.innerHTML = `
          <p><strong>Device Name:</strong> ${deviceName}</p>
          <p><strong>Start Time:</strong> ${startTime}</p>
          <p><strong>End Time:</strong> ${endTime}</p>
          <button class="edit-btn">Edit</button>
      `;
      
      // Append the schedule item to the schedule list
      var scheduleList = document.getElementById("schedule-list");
      scheduleList.appendChild(scheduleItem);
      
      // Clear the form inputs after adding schedule
      document.getElementById("schedule").reset();
  });
});

// Function to create schedule elements
function createScheduleElement(schedule) {
  const scheduleContainer = document.getElementById("schedule-container");
  const scheduleElement = document.createElement("div");
  scheduleElement.classList.add("schedule");
  scheduleElement.innerHTML = `
    <p>${schedule.name}</p>
    <p>${schedule.time}</p>
  `;
  scheduleContainer.appendChild(scheduleElement);
}

// Create schedule elements for each schedule
schedules.forEach(createScheduleElement);

// Event listener for "Create Schedule" button
document.getElementById("create-schedule-btn").addEventListener("click", () => {
  alert("Let's create a new schedule!");
});

document.addEventListener("DOMContentLoaded", function() {
  const scheduleContainer = document.getElementById('schedule');
  const addScheduleButton = document.getElementById('add-schedule');

  addScheduleButton.addEventListener('click', function() {
    addSchedule();
  });

  function addSchedule() {
    const scheduleItem = document.createElement('div');
    scheduleItem.classList.add('schedule-item');
    scheduleItem.innerHTML = `
      <label for="start-time">Start Time:</label>
      <input type="time" id="start-time">
      <label for="end-time">End Time:</label>
      <input type="time" id="end-time">
      <button class="delete-schedule">Delete</button>
    `;
    scheduleContainer.appendChild(scheduleItem);

    const deleteButtons = scheduleContainer.querySelectorAll('.delete-schedule');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function() {
        scheduleContainer.removeChild(scheduleItem);
      });
    });
  }
  document.addEventListener("DOMContentLoaded", function () {
    var addButton = document.getElementById("add-schedule-btn");
    var scheduleForm = document.getElementById("schedule");

    addButton.addEventListener("click", function () {
        var deviceName = document.getElementById("device-name").value;
        var startTime = document.getElementById("start-time").value;
        var endTime = document.getElementById("end-time").value;
        
        // Create a dialog box with schedule details
        var dialogBox = document.createElement("div");
        dialogBox.classList.add("dialog-box");
        dialogBox.innerHTML = `
            <p>Device Name: ${deviceName}</p>
            <p>Start Time: ${startTime}</p>
            <p>End Time: ${endTime}</p>
            <button class="edit-btn">Edit</button>
        `;
        
        // Append dialog box to the schedule list
        var scheduleList = document.getElementById("schedule-list");
        scheduleList.appendChild(dialogBox);
        
        // Clear the form inputs after adding schedule
        scheduleForm.reset();
        
        // Add event listener for the edit button in the dialog box
        var editButton = dialogBox.querySelector(".edit-btn");
        editButton.addEventListener("click", function () {
            // Implement edit functionality here
            console.log("Edit button clicked");
        });
    });
  });
});
