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

function AddSecurity(){
    document.getElementById("mod").style = "visibility: visible";
    document.getElementById("gate").style = "visibility: hidden";
    document.getElementById("secure").style = "visibility: visible";
    document.getElementById("unsecure").style = "visibility: hidden";

    // Execute the SQL query via AJAX
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log("Security added to Main Door");
        } else if (this.readyState == 4 && this.status != 200) {
            console.error("Failed to add security:", xhr.responseText);
            // Optionally, provide error feedback to the user
        }
    };
    xhr.open("POST", "add_security.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("action=addSecurity");
}

// Adjust content width when the window is resized
window.onresize = function(event) {
    adjustContentWidth();
}
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
var elements = document.querySelectorAll('.options');

// Iterate through each element and add the mouseover event listener
elements.forEach(function(element) {
    element.addEventListener('mouseover', function() {
        element.style.filter = 'drop-shadow(0 0 0.75rem yellow)';
        element.style.width = '90px';
        element.style.color = 'white';
    });

    // Add mouseout event listener to revert the background color
    element.addEventListener('mouseout', function() {
        element.style.filter = '';
        element.style.width = '80px';
        element.style.color = 'rgba(255, 255, 255, 0.719)';
    });
});

document.getElementById("room").addEventListener("change", function() {
    var selectedOption = this.value;
    var imageSrc = '';

    // Check if the selected option is the default one
    if (selectedOption === "Choose a Room") {
        // Set the image source to the default picture
        imageSrc = "room.jpg";
    } else {
        // Get the data-image attribute of the selected option
        imageSrc = this.options[this.selectedIndex].getAttribute("data-image");
    }

    // Update the image source
    document.getElementById("displayedImage").src = imageSrc;
});

// Add event listener to the displayed image
document.getElementById("displayedImage").addEventListener("click", function() {
    // Specify the URL of the page you want to open in a new tab/window
    var newPageUrl = "Rooms.php";

    // Open the new page in a new tab/window
    window.open(newPageUrl, "_self");
});

