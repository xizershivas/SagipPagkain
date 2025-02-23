const frmVolunteer = document.querySelector('#frmVolunteer');
const btnEditVolunteerList = document.getElementsByClassName('btn-edit-volunteer');
const btnDeleteVolunteerList = document.getElementsByClassName('btn-delete-volunteer');
const signUploaded = document.querySelector('#signUploaded');
const btnSave = document.querySelector('#btnSave');
let editData = {};
let intVolunteerId = 0;

function setFormData({ data }) {
    editData = data;
    frmVolunteer.elements.firstname.value = data.strFirstName;
    frmVolunteer.elements.lastname.value = data.strLastName;
    frmVolunteer.elements.gender.value = data.strGender;
    frmVolunteer.elements.birthdate.value = data.dtmDateOfBirth;
    frmVolunteer.elements.street.value = data.strStreet;
    frmVolunteer.elements.address.value = data.strAddress;
    frmVolunteer.elements.city.value = data.strCity;
    frmVolunteer.elements.region.value = data.strRegion;
    frmVolunteer.elements.zipcode.value = data.strZipCode;
    frmVolunteer.elements.country.value = data.strCountry;
    frmVolunteer.elements.contact.value = data.strContact;
    frmVolunteer.elements.email.value = data.strEmail;
    // Get the filename of the uploaded signature image
    signUploaded.innerHTML = `<b>Uploaded:</b> ${data.strSignFilePath.split('/').pop()}`;
}

function editVolunteer(e) {
    intVolunteerId = e.currentTarget.getAttribute('data-id');
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

    xmlhttp.open('GET', `../../../app/controllers/volunteerManagement.php?intVolunteerId=${intVolunteerId}`, true);
    xmlhttp.send();
}

function updateVolunteer(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("volunteerId", intVolunteerId);
    
    if (signUploaded.textContent != "" && this.elements.signature.value == "") {
        formData.append("signUploaded", editData.strSignFilePath);
    }

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

    xmlhttp.open('POST', '../../../app/controllers/volunteerManagement.php', true);
    xmlhttp.send(formData);
}

function deleteVolunteer(e) {
    intVolunteerId = parseInt(e.currentTarget.getAttribute('data-id'));
    const ysnConfirmed = window.confirm('Are you sure you want to delete this volunteer?');

    if (ysnConfirmed) {
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

        xmlhttp.open('DELETE', `../../../app/controllers/volunteerManagement.php`, true);
        xmlhttp.setRequestHeader('Content-Type', 'application/json');
        xmlhttp.send(JSON.stringify({ intVolunteerId: intVolunteerId }));
    }
}

// Attach Event Handler for Editing Volunteer
for (let btnEdit of btnEditVolunteerList) {
    btnEdit.addEventListener('click', editVolunteer);
}

for (let btnDelete of btnDeleteVolunteerList) {
    btnDelete.addEventListener('click', deleteVolunteer);
}

frmVolunteer.addEventListener('submit', updateVolunteer);