// User Form Variables
const frmUser = document.querySelector('#frmUser');
const btnEditUserList = document.getElementsByClassName('btn-edit-user');
const btnClose = document.querySelector('#btnClose');

// Show/Hide User Form
function toggleFormAddUser() {
    frmUser.classList.toggle('d-none');

    if (!frmUser.classList.contains('d-none')) {
        btnAddUser.textContent = 'Close Form';
    } else {
        btnAddUser.textContent = 'Add User';
    }
}

function editUser(e) {
    const intUserId = e.currentTarget.getAttribute('value');

    //  XMLHttpRequest - Get details of User using intUserId
}

for (let btnEdit of btnEditUserList) {
    btnEdit.addEventListener('click', editUser);
}

btnClose.addEventListener('click', toggleFormAddUser);