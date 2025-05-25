// User Form Variables
const frmUser = document.querySelector('#frmUser');
const frmAddUser = document.querySelector('#frmAddUser');
const btnEditUserList = document.getElementsByClassName('btn-edit-user');
const btnDeleteUserList = document.getElementsByClassName('btn-delete-user');
const btnSave = document.querySelector('#btnSave');
const btnClose = document.querySelector('#btnClose');
const btnShowHideList = document.getElementsByClassName('show-hide-password');
const password = document.querySelector('#password');
const confirmPassword = document.querySelector('#confirmPassword');
let intUserId = 0;

function setFormData({ data }) {
    frmUser.elements.user.value = data.strUsername;
    frmUser.elements.email.value = data.strEmail;
    frmUser.elements.active.checked = data.ysnActive ? true : false;
    frmUser.elements.admin.checked = data.ysnAdmin ? true : false;
    frmUser.elements.donor.checked = data.ysnDonor ? true : false;
    frmUser.elements.staff.checked = data.ysnStaff ? true : false;
    frmUser.elements.partner.checked = data.ysnPartner ? true : false;
}

function editUser(e) {
    intUserId = e.currentTarget.getAttribute('value');
    const xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const response = JSON.parse(this.responseText);

            if (this.status == 200) {
                setFormData(response);
            } else {
                // alert(response.data.message);
            }
        }
    };

    xmlhttp.open('GET', `../../../app/controllers/user.php?intUserId=${intUserId}`, true);
    xmlhttp.send();
}

function updateUser() {
    const userData = {
        intUserId: intUserId,
        strUsername: frmUser.elements.user.value,
        strEmail: frmUser.elements.email.value,
        ysnActive: frmUser.elements.active.checked ? true : false,
        ysnAdmin: frmUser.elements.admin.checked ? true : false,
        ysnDonor: frmUser.elements.donor.checked ? true : false,
        ysnStaff: frmUser.elements.staff.checked ? true : false,
        ysnPartner: frmUser.elements.partner.checked ? true : false,
    };

    const xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const response = JSON.parse(this.responseText);

            if (this.status == 200) {
                window.location.reload(); // Refresh to reload Data Table
            } else {
                alert(response.data.message);
            }
        }
    };

    xmlhttp.open('PUT', '../../../app/controllers/user.php', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/json');
    xmlhttp.send(JSON.stringify(userData));
}

function deleteUser(e) {
    const intUserId = parseInt(e.currentTarget.getAttribute('value'));
    const ysnConfirmed = window.confirm('Are you sure you want to delete this user?');

    if (ysnConfirmed) {
        const xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4) {
                const response = JSON.parse(this.responseText);
    
                if (this.status == 200) {
                    window.location.reload(); // Refresh to reload Data Table
                } else {
                    // alert(response.data.message);
                }
            }
        };

        xmlhttp.open('DELETE', `../../../app/controllers/user.php`, true);
        xmlhttp.setRequestHeader('Content-Type', 'application/json');
        xmlhttp.send(JSON.stringify({ intUserId: intUserId }));
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

async function addUser(e) {
    e.preventDefault();

    if (!this.checkValidity()) {
        this.classList.add('was-validated');
        return;
    }

    const formData = new FormData(this);

    try {
        const res = await fetch('../../../app/controllers/user.php', {
            method: 'POST',
            body: formData
        });

        const resData = await res.json();

        if (!res.ok) {
            throw new Error(resData.data.message);
        }
        
        alert(resData.data.message);
        window.location.reload();
    } catch (e) {
        alert(e.message);
    }
}

// Attach Event Handler for Editing User
for (let btnEdit of btnEditUserList) {
    btnEdit.addEventListener('click', editUser);
}

// Attach Event Handler for Deleting User
for (let btnDelete of btnDeleteUserList) {
    btnDelete.addEventListener('click', deleteUser);
}

for (let btnShowHide of btnShowHideList) {
    btnShowHide.addEventListener('click', showHidePassword);
}

btnSave.addEventListener('click', updateUser);
frmAddUser.addEventListener('submit', addUser);