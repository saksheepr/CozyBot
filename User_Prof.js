function openAvatarDialog() {
    document.getElementById('avatar-input').click();
}

document.getElementById('avatar-input').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function() {
        const imageDataUrl = reader.result;
        const avatarImg = document.getElementById('avatar');
        avatarImg.src = imageDataUrl;
    };

    reader.readAsDataURL(file);
});

const form = document.getElementById('profile-form');

form.addEventListener('submit', (event) => {
    event.preventDefault();

    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const bio = document.getElementById('bio').value;

    console.log('Saving profile data:', { name, email, bio });
    alert('Profile saved successfully!');
    form.reset();
});