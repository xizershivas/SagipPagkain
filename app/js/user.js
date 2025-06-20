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
const uploadDocInput = document.querySelector('#uploadDocInput');
const uploadDocPreview = document.querySelector('#uploadDocPreview');
const btnViewDoc = document.querySelector('#btnViewDoc');
const btnDownloadDoc = document.querySelector('#btnDownloadDoc');
const accountType = document.querySelector('#accountType');
const foodBankSelect = document.querySelector('#foodBankSelect');
const foodBankSelectContainer = document.querySelector('#foodBankSelectContainer');
const userTypeFilter = document.querySelector('#userTypeFilter');
const userDataTableBody = document.querySelector('#userDataTable tbody');
let intUserId = 0;


function setFormData({ data }) {
    frmUser.elements.userId.value = data.intUserId;
    frmUser.elements.user.value = data.strUsername;
    frmUser.elements.userEmail.value = data.strEmail;
    frmUser.elements.fullName.value = data.strFullName;
    frmUser.elements.userContact.value = data.strContact;
    frmUser.elements.address.value = data.strAddress;
    frmUser.elements.salary.value = data.dblSalary;
    frmUser.elements.active.checked = data.ysnActive ? true : false;
    frmUser.elements.admin.checked = data.ysnAdmin ? true : false;
    frmUser.elements.donor.checked = data.ysnDonor ? true : false;
    frmUser.elements.foodbank.checked = data.ysnFoodBank ? true : false;
    frmUser.elements.beneficiary.checked = data.ysnBeneficiary ? true : false;
    // PDF Preview
    uploadDocPreview.src = "";
    if (data.strDocument) {
        btnViewDoc.classList.remove('d-none');
        btnViewDoc.setAttribute("href", data.strDocument);
        btnDownloadDoc.classList.remove('d-none');
        btnDownloadDoc.setAttribute("href", data.strDocument);
        uploadDocPreview.src = data.strDocument;
    }

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

function updateUser(e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append('_method', 'PUT');
    const xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const response = JSON.parse(this.responseText);

            if (this.status == 200) {
                alert(response.data.message);
                window.location.reload(); // Refresh to reload Data Table
            } else {
                alert(response.data.message);
            }
        }
    };

    xmlhttp.open('POST', '../../../app/controllers/user.php', true);
    xmlhttp.send(formData);
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

function documentPreview() {
    const file = this.files[0];
    if (file && file.type === 'application/pdf') {
        const fileReader = new FileReader();
        fileReader.onload = function (e) {
            const docData = e.target.result;
            uploadDocPreview.src = docData;
            uploadDocPreview.style.display = 'block';
        };
        fileReader.readAsDataURL(file);
    } else {
        alert("Please select a valid PDF file.");
        uploadDocPreview.style.display = 'none';
    }
}

function toggleFoodBankSelect(e) {
    if (e.target.value === "foodbank") {
        foodBankSelectContainer.classList.remove('d-none');
    } else {
        foodBankSelect.value = "";
        foodBankSelectContainer.classList.add('d-none');
    }
}

function setTableData({ data }) {
    userTable.clear();

    const newRows = data.map(user => [
        user.strUsername,
        user.strEmail,
        user.ysnActive ? "<span class='ysn-true'>Yes</span>" : "<span class='ysn-false'>No</span>",
        user.ysnAdmin ? "<span class='ysn-true'>Yes</span>" : "<span class='ysn-false'>No</span>",
        user.ysnDonor ? "<span class='ysn-true'>Yes</span>" : "<span class='ysn-false'>No</span>",
        user.ysnFoodBank ? "<span class='ysn-true'>Yes</span>" : "<span class='ysn-false'>No</span>",
        user.ysnBeneficiary ? "<span class='ysn-true'>Yes</span>" : "<span class='ysn-false'>No</span>",
        `<a class="btn-edit-user" data-bs-toggle="modal" data-bs-target="#staticBackdrop" href="javascript:void(0)" value="${user.intUserId}"><i class='bi bi-pencil-square'></i></a>`
    ]);

    userTable.rows.add(newRows).draw();
}

async function filterUserType(e) {
    const userType = e.target.value;
    debugger;

    try {
        const res = await fetch(`../../../app/controllers/user.php?userType=${userType}`, {
            method: 'GET'
        });

        const resData = await res.json();

        if (!res.ok) {
            throw new Error(resData.data.message);
        }
        setTableData(resData);
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

frmUser.addEventListener('submit', updateUser);
frmAddUser.addEventListener('submit', addUser);
uploadDocInput.addEventListener('change', documentPreview);
accountType.addEventListener('change', toggleFoodBankSelect);
userTypeFilter.addEventListener('change', filterUserType);