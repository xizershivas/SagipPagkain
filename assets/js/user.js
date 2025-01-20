// User Form Variables
const btnAddUser = document.querySelector('#btnAddUser');
const btnClose = document.querySelector('#btnClose');
const frmAddUser = document.querySelector('#frmAddUser');

// Table Action Variables
const btnEditUser = document.getElementsByClassName('btn-edit-user');
const btnDeleteUser = document.querySelector('.btn-delete-user');

// Show/Hide User Form
function toggleFormAddUser() {
    frmAddUser.classList.toggle('d-none');

    if (!frmAddUser.classList.contains('d-none')) {
        btnAddUser.textContent = 'Close Form';
    } else {
        btnAddUser.textContent = 'Add User';
    }
}

// User Form Event Listeners
btnAddUser.addEventListener('click', toggleFormAddUser);
btnClose.addEventListener('click', toggleFormAddUser);