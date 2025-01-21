// User Form Variables
const frmUser = document.querySelector('#frmUser');
const btnEditUserList = document.getElementsByClassName('btn-edit-user');
const btnSave = document.querySelector('#btnSave');
const btnClose = document.querySelector('#btnClose');
const txtUser = frmUser.children[0].elements.user;
const txtEmail = frmUser.children[0].elements.email;
const chkEnabled = frmUser.children[0].elements.enabled;
const chkApproved = frmUser.children[0].elements.approved;
let intUserId = 0;

// Show/Hide User Form
function toggleFormAddUser() {
    frmUser.classList.toggle('d-none');
}

function setFormData(response) {
    const data = JSON.parse(response);
    txtUser.value = data.strUsername;
    txtEmail.value = data.strEmail;
    chkEnabled.checked = data.ysnEnabled ? true : false;
    chkApproved.checked = data.ysnApproved ? true : false;
}

function editUser(e) {
    if (frmUser.classList.contains('d-none')) {
        toggleFormAddUser();
    }

    intUserId = e.currentTarget.getAttribute('value');
    const xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            setFormData(this.responseText);
        }
    }

    xmlhttp.open('GET', `request.php?intUserId=${intUserId}`, true);
    xmlhttp.setRequestHeader('Content-Type', 'application/json');
    xmlhttp.send();
}

for (let btnEdit of btnEditUserList) {
    btnEdit.addEventListener('click', editUser);
}

function updateUser() {
    const userData = {
        intUserId: intUserId,
        strUsername: txtUser.value,
        strEmail: txtEmail.value,
        ysnEnabled: chkEnabled.checked ? true : false,
        ysnApproved: chkApproved.checked ? true : false
    };

    const xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            toggleFormAddUser();
            window.location.reload(); // Refresh to reload Data Table
        }
    }

    xmlhttp.open('POST', 'request.php', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/json');
    xmlhttp.send(JSON.stringify(userData));
}

btnClose.addEventListener('click', toggleFormAddUser);
btnSave.addEventListener('click', updateUser);