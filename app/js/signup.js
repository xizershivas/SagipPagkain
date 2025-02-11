const userType = document.querySelector('#userType');
const divOther = document.querySelector('#divOther');
const inputSpecify = document.querySelector('#inputSpecify');
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
            inputSpecify.removeAttribute('required');
            break;
        case 'other': 
            divOther.classList.remove('d-none');
            inputSpecify.required = true;
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

userType.addEventListener('change', accountTypeSelect);

for (let btnShowHide of btnShowHideList) {
    btnShowHide.addEventListener('click', showHidePassword);
}