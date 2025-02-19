document.getElementById('registrationForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const password = document.getElementById('password').value;
    const verifyPassword = document.getElementById('verifyPassword').value;

    if (password !== verifyPassword) {
        alert('Passwords do not match.');
        return;
    }

    // Here you can add code to handle form submission, like sending data to a server
    alert('Form submitted successfully!');
});