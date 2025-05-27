const frmSignUp = document.querySelector('#frmSignUp');
const accountType = document.querySelector('#accountType');
const btnShowHideList = document.getElementsByClassName('show-hide-password');
const password = document.querySelector('#password');
const confirmPassword = document.querySelector('#confirmPassword');

function showHidePassword(e) {
    if (e.target.classList.contains('bi-eye-fill')) {
        e.target.classList.remove('bi-eye-fill');
        e.target.classList.add('bi-eye-slash-fill');
        
        // Show password
        if (e.target.id == "eyePassword") {
            password.type = "text"; // Password input
        } else {
            confirmPassword.type = "text"; // Confirm password input
        }
    } else {
        e.target.classList.remove('bi-eye-slash-fill');
        e.target.classList.add('bi-eye-fill');

        // Hide password
        if (e.target.id == "eyePassword") {
            password.type = "password"; // Password input
        } else {
            confirmPassword.type = "password"; // Confirm password input
        }
    }
}

function signUp(e) {
    e.preventDefault();
    const formData = new FormData(this);

    const xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const response = JSON.parse(this.responseText);

            if (this.status == 200 || this.status == 201) {
                alert(response.data.message);
                window.location.href = '../../../index.php';
            } else {
                alert(response.data.message);
            }
        }
    };

    xmlhttp.open('POST', '../../../app/controllers/signup.php', true);
    xmlhttp.send(formData);
}

for (let btnShowHide of btnShowHideList) {
    btnShowHide.addEventListener('click', showHidePassword);
}

frmSignUp.addEventListener('submit', signUp);