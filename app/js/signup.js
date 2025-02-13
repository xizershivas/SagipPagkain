const frmSignUp = document.querySelector('#frmSignUp');
const accountType = document.querySelector('#accountType');
const divOther = document.querySelector('#divOther');
const specifyOther = document.querySelector('#specifyOther');
const btnShowHideList = document.getElementsByClassName('show-hide-password');
const password = document.querySelector('#password');
const confirmPassword = document.querySelector('#confirmPassword');

// To show input text if Other is selected and hide if not
function accountTypeSelect(e) {
    const accountType = e.target.value;

    switch (accountType) {
        case 'donor':
        case 'ngo': 
            divOther.classList.add('d-none'); 
            specifyOther.removeAttribute('required');
            break;
        case 'other': 
            divOther.classList.remove('d-none');
            specifyOther.required = true;
        break;
    }
}

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

            if (this.status == 200) {
                alert(response.data.message);
                window.location.href = './login.php';
            } else {
                alert(response.data.message);
            }
        }
    };

    xmlhttp.open('POST', 'app/controllers/signup.php', true);
    xmlhttp.send(formData);
}

for (let btnShowHide of btnShowHideList) {
    btnShowHide.addEventListener('click', showHidePassword);
}

accountType.addEventListener('change', accountTypeSelect);
frmSignUp.addEventListener('submit', signUp);