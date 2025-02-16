// User Form Variables
const frmUser = document.querySelector('#frmUser');
const btnEditUserList = document.getElementsByClassName('btn-edit-user');
const btnDeleteUserList = document.getElementsByClassName('btn-delete-user');
const btnSave = document.querySelector('#btnSave');
const btnClose = document.querySelector('#btnClose');
let intUserId = 0;

function setFormData({ data }) {
    frmUser.elements.user.value = data.strUsername;
    frmUser.elements.email.value = data.strEmail;
    frmUser.elements.enabled.checked = data.ysnEnabled ? true : false;
    frmUser.elements.approved.checked = data.ysnApproved ? true : false;
    frmUser.elements.admin.checked = data.ysnAdmin ? true : false;
    frmUser.elements.donor.checked = data.ysnDonor ? true : false;
    frmUser.elements.ngo.checked = data.ysnNgo ? true : false;
    frmUser.elements.other.checked = data.ysnOther ? true : false;
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
        ysnEnabled: frmUser.elements.enabled.checked ? true : false,
        ysnApproved: frmUser.elements.approved.checked ? true : false,
        ysnAdmin: frmUser.elements.admin.checked ? true : false,
        ysnDonor: frmUser.elements.donor.checked ? true : false,
        ysnNgo: frmUser.elements.ngo.checked ? true : false,
        ysnOther: frmUser.elements.other.checked ? true : false,
    };

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

// Attach Event Handler for Editing User
for (let btnEdit of btnEditUserList) {
    btnEdit.addEventListener('click', editUser);
}

// Attach Event Handler for Deleting User
for (let btnDelete of btnDeleteUserList) {
    btnDelete.addEventListener('click', deleteUser);
}

btnSave.addEventListener('click', updateUser);