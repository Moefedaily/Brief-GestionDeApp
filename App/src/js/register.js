const formRegister = document.getElementById('registrationForm');
const errorMessage = document.getElementById('error-message');

formRegister.addEventListener('submit', (event) => {
    event.preventDefault();
    registerUser();
});

function registerUser() {
    const formData = new FormData(formRegister);
    formData.append('activation', '0');
    fetch('/cours/Brief-GestionDeApp/register', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        }
    })
    .then(data => {
        if (data.success) {
            console.log('Registration successful');
            window.location.href = '/cours/Brief-GestionDeApp/login';
        } else {
            displayErrorMessage(data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        displayErrorMessage(error.message);
    });
}

function displayErrorMessage(message) {
    errorMessage.textContent = message;
    errorMessage.style.display = 'block';
}