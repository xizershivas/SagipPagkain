const frmDonation = document.querySelector('#frmDonation');
const btnEditDonationList = document.getElementsByClassName('btn-edit-donation');
const btnDeleteDonationList = document.getElementsByClassName('btn-delete-donation');
const transportStatus = document.querySelector('#transportStatus');
const labelTransportStatus = document.querySelector('#labelTransportStatus');
const uploadDocumentation = document.querySelector('#uploadDocumentation');
const mediaSelectedLoc = document.querySelector('#mediaSelectedLoc');
const docsUploadedMedia = document.querySelector('#docsUploadedMedia');
const btnSave = document.querySelector('#btnSave');
let intDonationId = 0;
let docsUploaded = "";

function mediaSelected(e) {
    const files = e.target.files;
    mediaSelectedLoc.innerHTML = '';

    let selectedFiles = Array.from(files);

    for (let file of files) {
        const reader = new FileReader();
        
        reader.onload = function(evt) {
            const fileType = file.type.split('/')[0]; // Check if it's image or video

            // Create a container div for each media element
            const mediaContainer = document.createElement('div');
            mediaContainer.style.position = 'relative';
            mediaContainer.style.display = 'inline-block';
            mediaContainer.style.margin = '10px';

            let mediaElement;

            if (fileType === 'image') {
                // Create an image element for image files
                mediaElement = document.createElement('img');
                mediaElement.src = evt.target.result;
                mediaElement.style.maxWidth = '5em'; // optional styling
                mediaElement.style.margin = '10px';
            } else if (fileType === 'video') {
                // Create a video element for video files
                mediaElement = document.createElement('video');
                mediaElement.src = evt.target.result;
                mediaElement.controls = true;
                mediaElement.style.maxWidth = '10em'; // optional styling
                mediaElement.style.margin = '10px';
            }

            // Create the close button (x)
            const closeButton = document.createElement('button');
            closeButton.textContent = 'X';
            closeButton.style.position = 'absolute';
            closeButton.style.top = '0';
            closeButton.style.right = '0';
            closeButton.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
            closeButton.style.color = 'white';
            closeButton.style.border = 'none';
            closeButton.style.borderRadius = '50%';
            closeButton.style.cursor = 'pointer';
            closeButton.style.fontSize = '14px';
            closeButton.style.padding = '5px';

            // Append the close button and media element to the container
            mediaContainer.appendChild(mediaElement);
            mediaContainer.appendChild(closeButton);

            // Add event listener to the close button to remove the media element
            closeButton.addEventListener('click', function() {
                mediaContainer.remove();

                // Update the file input list by removing the corresponding file
                selectedFiles = selectedFiles.filter(f => f !== file);

                // Reassign the updated list of files back to the input field
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(f => dataTransfer.items.add(f));
                uploadDocumentation.files = dataTransfer.files;
            });

            // Append the media container to the main div
            mediaSelectedLoc.appendChild(mediaContainer);
        }

        reader.readAsDataURL(file);
    }
}

function statusChange() {
    if (transportStatus.checked) {
        labelTransportStatus.innerHTML = 'Status <span class="ysn-true">Received</span>';
    } else {
        labelTransportStatus.innerHTML = 'Status <span class="ysn-false">Pending</span>';
    }
}

function setFormData({ data }) {
    docsUploaded = data;
    frmDonation.elements.donor.value = data.strDonorName;
    frmDonation.elements.date.value = data.dtmDate;
    frmDonation.elements.title.value = data.strTitle;
    frmDonation.elements.description.value = data.strDescription;
    frmDonation.elements.pickupLocation.value = data.strPickupLocation;
    // NOTE: TO BE ADDED FILE HANDLING
    frmDonation.elements.transportStatus.checked = data.ysnStatus ? true : false;
    labelTransportStatus.innerHTML = data.ysnStatus ? 'Status <span class="ysn-true">Received</span>' : 'Status <span class="ysn-false">Pending</span>';
    frmDonation.elements.remarks.value = data.strRemarks;
    const docsPathArray = docsUploaded.strDocFilePath.split(',');
    const docs = docsPathArray.map(filePath => filePath.split(/[/\\]/).pop());
    docsUploadedMedia.innerHTML = docs.join('<br>');
}

function editDonation(e) {
    intDonationId = e.currentTarget.getAttribute('data-id');
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

    xmlhttp.open('GET', `../../../app/controllers/donationManagement.php?intDonationId=${intDonationId}`, true);
    xmlhttp.send();
}

function updateDonation(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('donationId', intDonationId);

    if (docsUploaded.strDocFilePath) {
        formData.append('docsUploadedPaths', docsUploaded.strDocFilePath);
    }

    if (this.elements.uploadDocumentation.files.length > 3) {
        alert('You can only select 3 files in total');
    } else {
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

        xmlhttp.open('POST', '../../../app/controllers/donationManagement.php', true);
        xmlhttp.send(formData);
    }
}

function deleteDonation(e) {
    intDonationId = parseInt(e.currentTarget.getAttribute('data-id'));
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

        xmlhttp.open('DELETE', `../../../app/controllers/donationManagement.php`, true);
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

uploadDocumentation.addEventListener('change', mediaSelected);
transportStatus.addEventListener('change', statusChange);
frmDonation.addEventListener('submit', updateDonation);