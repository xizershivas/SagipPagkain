const frmDonate = document.querySelector("#frmDonate");

function donate(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const xmlhttp = new XMLHttpRequest();

    if (!this.elements.date.value) {
        alert('Invalid Date');
    } else {
        if (this.elements.verification.files.length > 2) {
            alert('You can only select 2 files in total');
        } else {
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
            };
        }

        xmlhttp.open('POST', '../../../app/controllers/donate.php', true);
        xmlhttp.send(formData);
    }
}

frmDonate.addEventListener('submit', donate);