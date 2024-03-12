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
          <p><strong>Device Name: </strong> ${deviceName}</p>
          <p><strong>Start Time: </strong> ${startTime}</p>
          <p><strong>End Time: </strong> ${endTime}</p>
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

  const taskInput = document.getElementById("task");
  const priorityInput = document.getElementById("priority");
  const deadlineInput = document.getElementById("deadline");
  const addTaskButton = document.getElementById("add-task");
  const taskList = document.getElementById("task-list");
  
  addTaskButton.addEventListener("click", () => {
      const task = taskInput.value;
      const priority = priorityInput.value;
      const deadline = deadlineInput.value;
      if (task.trim() === "" || deadline === "") {
          alert("Please select an upcoming date for the deadline.")
          return; // Don't add task if task or deadline is empty
      }
  
      const selectedDate = new Date(deadline);
      const currentDate = new Date();
  
      if (selectedDate <= currentDate) {
          alert("Please select an upcoming date for the deadline.");
          return; // Don't add task if deadline is not in the future
      }
  
  
      const taskItem = document.createElement("div");
      taskItem.classList.add("task");
      taskItem.innerHTML = `
      <p>${task}</p>
      <p>Priority: ${priority}</p>
      <p>Deadline: ${deadline}</p>
      <button class="mark-done">Mark Done</button>
    `;
  
      taskList.appendChild(taskItem);
  
      taskInput.value = "";
      priorityInput.value = "top";
      deadlineInput.value = "";
  });
  
  taskList.addEventListener("click", (event) => {
      if (event.target.classList.contains("mark-done")) {
          const taskItem = event.target.parentElement;
          taskItem.style.backgroundColor = "#f2f2f2";
          event.target.disabled = true;
      }
  });

  var editButton = scheduleItem.querySelector(".edit-btn");
    editButton.addEventListener("click", function () {
        editSchedule(scheduleItem);
    });

  function editSchedule(scheduleItem) {
    var deviceName = scheduleItem.querySelector("p:nth-child(1)").innerText;
    var startTime = scheduleItem.querySelector("p:nth-child(2)").innerText.split(": ")[1];
    var endTime = scheduleItem.querySelector("p:nth-child(3)").innerText.split(": ")[1];

    // Fill the form fields with schedule details
    document.getElementById("device-name").value = deviceName;
    document.getElementById("start-time").value = startTime;
    document.getElementById("end-time").value = endTime;

    // Remove the edited schedule item from the list
    scheduleItem.remove();
  }
});
