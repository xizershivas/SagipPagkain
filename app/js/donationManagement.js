const frmDonation = document.querySelector('#frmDonation');
const btnEditDonationList = document.getElementsByClassName('btn-edit-donation');
const btnDeleteDonationList = document.getElementsByClassName('btn-delete-donation');
const transportStatus = document.querySelector('#transportStatus');
const labelTransportStatus = document.querySelector('#labelTransportStatus');
const btnSave = document.querySelector('#btnSave');
let intDonationId = 0;

function statusChange() {
    if (transportStatus.checked) {
        labelTransportStatus.innerHTML = 'Status <span class="ysn-true">Received</span>';
    } else {
        labelTransportStatus.innerHTML = 'Status <span class="ysn-false">Pending</span>';
    }
}

function setFormData({ data }) {
    frmDonation.elements.donor.value = data.strDonorName;
    frmDonation.elements.date.value = data.dtmDate;
    frmDonation.elements.title.value = data.strTitle;
    frmDonation.elements.description.value = data.strDescription;
    frmDonation.elements.pickupLocation.value = data.strPickupLocation;
    // NOTE: TO BE ADDED FILE HANDLING
    frmDonation.elements.transportStatus.checked = data.ysnStatus ? true : false;
    labelTransportStatus.innerHTML = data.ysnStatus ? 'Status <span class="ysn-true">Received</span>' : 'Status <span class="ysn-false">Pending</span>';
    frmDonation.elements.remarks.value = data.strRemarks;
}

function editDonation(e) {
    intDonationId = e.currentTarget.getAttribute('value');
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

    xmlhttp.open('GET', `app/controllers/donationManagement.php?intDonationId=${intDonationId}`, true);
    xmlhttp.send();
}

function updateDonation(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append("donationId", intDonationId);

    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const response = JSON.parse(this.responseText);

            if (this.status == 200 || this.status == 202) {
                window.location.reload();
            } else {
                alert(response.data.message);
            }
        }
    };

    xmlhttp.open('POST', 'app/controllers/donationManagement.php', true);
    xmlhttp.send(formData);
}

function deleteDonation(e) {
    intDonationId = parseInt(e.currentTarget.getAttribute('value'));
    const ysnConfirmed = window.confirm('Are you sure you want to delete this donation record?');

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

        xmlhttp.open('DELETE', `app/controllers/donationManagement.php`, true);
        xmlhttp.setRequestHeader('Content-Type', 'application/json');
        xmlhttp.send(JSON.stringify({ intDonationId: intDonationId }));
    }
}

// Attach Event Handler for Editing Donation
for (let btnEdit of btnEditDonationList) {
    btnEdit.addEventListener('click', editDonation);
}

for (let btnDelete of btnDeleteDonationList) {
    btnDelete.addEventListener('click', deleteDonation);
}

transportStatus.addEventListener('change', statusChange);
frmDonation.addEventListener('submit', updateDonation);