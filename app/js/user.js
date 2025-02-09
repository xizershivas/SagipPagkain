// User Form Variables
const frmEditUser = document.querySelector('#frmEditUser');
const frmUser = document.querySelector('#frmUser');
const btnEditUserList = document.getElementsByClassName('btn-edit-user');
const btnDeleteUserList = document.getElementsByClassName('btn-delete-user');
const btnSave = document.querySelector('#btnSave');
const btnClose = document.querySelector('#btnClose');
const txtUser = frmUser.elements.user;
const txtEmail = frmUser.elements.email;
const chkEnabled = frmUser.elements.enabled;
const chkApproved = frmUser.elements.approved;
const chkAdmin = frmUser.elements.admin;
const chkDonor = frmUser.elements.donor;
const chkOther = frmUser.elements.other;
let intUserId = 0;

// Show/Hide User Form
function toggleFormAddUser() {
    frmEditUser.classList.toggle('d-none');
}

function setFormData(response) {
    const data = JSON.parse(response);
    txtUser.value = data.strUsername;
    txtEmail.value = data.strEmail;
    chkEnabled.checked = data.ysnEnabled ? true : false;
    chkApproved.checked = data.ysnApproved ? true : false;
    chkAdmin.checked = data.ysnAdmin ? true : false;
    chkDonor.checked = data.ysnDonor ? true : false;
    chkOther.checked = data.ysnOther ? true : false;
}

function editUser(e) {
    if (frmEditUser.classList.contains('d-none')) {
        toggleFormAddUser();
    }

    intUserId = e.currentTarget.getAttribute('value');
    const xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            setFormData(this.responseText);
        }
    }

    xmlhttp.open('GET', `app/controllers/user.php?intUserId=${intUserId}`, true);
    xmlhttp.setRequestHeader('Content-Type', 'application/json');
    xmlhttp.send();
}

function updateUser() {
    const userData = {
        intUserId: intUserId,
        strUsername: txtUser.value,
        strEmail: txtEmail.value,
        ysnEnabled: chkEnabled.checked ? true : false,
        ysnApproved: chkApproved.checked ? true : false,
        ysnAdmin: chkAdmin.checked ? true : false,
        ysnDonor: chkDonor.checked ? true : false,
        ysnOther: chkOther.checked ? true : false,
    };

    const xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            toggleFormAddUser();
            window.location.reload(); // Refresh to reload Data Table
        }
    }

    xmlhttp.open('PUT', 'app/controllers/user.php', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/json');
    xmlhttp.send(JSON.stringify(userData));
}

function deleteUser(e) {
    const intUserId = e.currentTarget.getAttribute('value');
    const ysnConfirmed = window.confirm('Are you sure you want to delete this user?');

    if (ysnConfirmed) {
        const xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                window.location.reload();
                // document.querySelector(`.btn-delete-user[value="${intUserId}]"`);
                console.log(this.responseText);
            }
        }

        xmlhttp.open('DELETE', `app/controllers/user.php`, true);
        xmlhttp.setRequestHeader('Content-Type', 'application/json');
        xmlhttp.send(JSON.stringify({ intUserId: intUserId }));
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

btnClose.addEventListener('click', toggleFormAddUser);
btnSave.addEventListener('click', updateUser);