const frmDonation = document.querySelector('#frmDonation');
const btnEditDonationList = document.getElementsByClassName('btn-edit-donation');
const status = document.querySelector('#status');
const labelStatus = document.querySelector('#labelStatus');
let intDonationId = 0;

function statusChange() {
    if (status.checked) {
        labelStatus.innerHTML = 'Status <span class="ysn-true">Received</span>';
    } else {
        labelStatus.innerHTML = 'Status <span class="ysn-false">Pending</span>';
    }
}

function setFormData({ data }) {
    frmDonation.elements.donor.value = data.strDonorName;
    frmDonation.elements.date.value = data.dtmDate;
    frmDonation.elements.title.value = data.strTitle;
    frmDonation.elements.description.value = data.strDescription;
    frmDonation.elements.pickupLocation.value = data.strPickupLocation;
    // NOTE: TO BE ADDED FILE HANDLING
    frmDonation.elements.status.checked = data.ysnStatus ? true : false;
    labelStatus.innerHTML = data.ysnStatus ? 'Status <span class="ysn-true">Received</span>' : 'Status <span class="ysn-false">Pending</span>';
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

// Attach Event Handler for Editing Donation
for (let btnEdit of btnEditDonationList) {
    btnEdit.addEventListener('click', editDonation);
}

status.addEventListener('change', statusChange);