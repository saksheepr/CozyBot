const testimonials = document.querySelectorAll('.testimonial');
    let currentTestimonial = 0;

    function changeTestimonial() {
        testimonials[currentTestimonial].classList.remove('active-testimonial');
        currentTestimonial = (currentTestimonial + 1) % testimonials.length;
        testimonials[currentTestimonial].classList.add('active-testimonial');
    }

    setInterval(changeTestimonial, 3000);

    // Slideshow functionality
    const images = document.querySelectorAll('.sub-column img');

    function changeImage() {
        const activeIndex = Array.from(images).findIndex(img => img.classList.contains('active'));
        images[activeIndex].classList.remove('active');
        const nextIndex = (activeIndex + 1) % images.length;
        images[nextIndex].classList.add('active');
    }

    setInterval(changeImage, 2000); // Change image every 5 seconds
    images[0].classList.add('active');

    document.addEventListener('DOMContentLoaded', function () {
    const navLinks = document.querySelectorAll('nav a');
    const sections = document.querySelectorAll('section');

    // Function to highlight the active section in the navbar
    function highlightNavbarItem() {
        const scrollPosition = window.scrollY;

        sections.forEach((section, index) => {
            const top = section.offsetTop - 50;
            const bottom = top + section.offsetHeight;

            if (scrollPosition >= top && scrollPosition < bottom) {
                // Remove the 'active' class from all navigation links
                navLinks.forEach(link => link.classList.remove('active'));
                
                // Add the 'active' class to the corresponding navigation link
                navLinks[index].classList.add('active');
            }
        });
    }

    // Add scroll event listener to highlight the active section in the navbar
    window.addEventListener('scroll', highlightNavbarItem);

    // Add click event listeners to the navigation links to remove 'active' class from all links
    // and add 'active' class to the clicked link
    navLinks.forEach(link => {
        link.addEventListener('click', function () {
            navLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');
        });
    });
});

    document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');

            form.addEventListener('submit', function (event) {
                // Validate Name
                const nameInput = document.getElementById('name');
                const nameValue = nameInput.value.trim();
                const nameRegex = /^[a-zA-Z\s]+$/;

                if (!nameRegex.test(nameValue)) {
                    alert('Please enter a valid name.');
                    event.preventDefault();
                    return;
                }

                // Validate Email
                const emailInput = document.getElementById('email');
                const emailValue = emailInput.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!emailRegex.test(emailValue)) {
                    alert('Please enter a valid email address.');
                    event.preventDefault();
                    return;
                }
            });
        });