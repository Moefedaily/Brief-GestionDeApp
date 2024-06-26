const formLogin = document.getElementById('loginForm');
formLogin.addEventListener('submit', (event) => {
    event.preventDefault(); 
    loginUser();
});

function loginUser() {
    const formData = new FormData(formLogin);
    fetch('/cours/Brief-GestionDeApp/login', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.status === "success") {
            
     window.location.href = "/cours/Brief-GestionDeApp/dashboard";
        } else {
            document.querySelector(".champVideConnexion").innerText = data.message;
        }
    })
    .catch(error => {
        console.error("Error:", error);
    });
}

