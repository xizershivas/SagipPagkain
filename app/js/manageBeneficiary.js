const frmBeneficiary = document.querySelector('#frmBeneficiary');
const frmAddBeneficiary = document.querySelector('#frmAddBeneficiary');
const btnEditBeneficiaryList = document.getElementsByClassName('btn-edit-beneficiary');
const btnDeleteBeneficiaryList = document.getElementsByClassName('btn-delete-beneficiary');
const btnSave = document.querySelector('#btnSave');
let intBeneficiaryId = 0;

function setFormData({ data }) {
    frmBeneficiary.elements.name.value = data.strName;
    frmBeneficiary.elements.contact.value = data.strContact;
    frmBeneficiary.elements.email.value = data.strEmail;
    frmBeneficiary.elements.address.value = data.strAddress;
    frmBeneficiary.elements.salary.value = data.dblSalary;
}

function addBeneficiary(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const response = JSON.parse(this.responseText);

            if (this.status == 200) {
                alert(response.data.message);
                window.location.reload();
            } else {
                alert(response.data.message);
            }
        }
    }

    xmlhttp.open('POST', '../../../app/controllers/manageBeneficiary.php', true);
    xmlhttp.send(formData);
}

function editBeneficiary(e) {
    intBeneficiaryId = e.currentTarget.getAttribute('data-id');
    const xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const response = JSON.parse(this.responseText);

            if (this.status == 200) {
                setFormData(response);
            } else {
                alert(response.data.message);
            }
        }
    };

    xmlhttp.open('GET', `../../../app/controllers/manageBeneficiary.php?intBeneficiaryId=${intBeneficiaryId}`, true);
    xmlhttp.send();
}

function updateBeneficiary(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append("beneficiaryId", intBeneficiaryId);

    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const response = JSON.parse(this.responseText);

            if (this.status == 200 || this.status == 202) {
                alert(response.data.message);
                window.location.reload();
            } else {
                alert(response.data.message);
            }
        }
    };

    xmlhttp.open('POST', '../../../app/controllers/manageBeneficiary.php', true);
    xmlhttp.send(formData);
}

function deleteBeneficiary(e) {
    intBeneficiaryId = parseInt(e.currentTarget.getAttribute('data-id'));
    const ysnConfirmed = window.confirm('Are you sure you want to delete this beneficiary?');

    if (ysnConfirmed) {
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

        xmlhttp.open('DELETE', `../../../app/controllers/manageBeneficiary.php`, true);
        xmlhttp.setRequestHeader('Content-Type', 'application/json');
        xmlhttp.send(JSON.stringify({ intBeneficiaryId: intBeneficiaryId }));
    }
}

// Attach Event Handler for Editing Beneficiary
for (let btnEdit of btnEditBeneficiaryList) {
    btnEdit.addEventListener('click', editBeneficiary);
}

for (let btnDelete of btnDeleteBeneficiaryList) {
    btnDelete.addEventListener('click', deleteBeneficiary);
}

frmBeneficiary.addEventListener('submit', updateBeneficiary);
frmAddBeneficiary.addEventListener('submit', addBeneficiary);