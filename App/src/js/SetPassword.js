// In set-password.js

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('passwordForm');

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        setPassword();
    });
        function setPassword(){
        const formData = new FormData(form);
        fetch(form.action, {
        method: 'POST',
        body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.success) {
                alert(data.message);
                window.location.href = '/cours/Brief-GestionDeApp/login';
            } else {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
        }
});