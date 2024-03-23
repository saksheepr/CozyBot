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

    // Determine the image source based on the selected option
    switch (selectedOption) {
        case "Bedroom":
            imageSrc = "Bedroom.jpg";
            break;
        case "Living Room":
            imageSrc = "LivingRoom.jpg";
            break;
        case "Kitchen":
            imageSrc = "Kitchen.jpg";
            break;
        case "Kids Room":
            imageSrc = "KidsRoom.jpg";
            break;
        default:
            imageSrc = "SmartLighting.jpg"; // default image
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

