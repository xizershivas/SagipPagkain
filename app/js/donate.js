const frmDonate = document.querySelector("#frmDonate");

function donate(e) {
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
                // alert(response.data.message);
            }
        }
    };

    xmlhttp.open('POST', '../../../app/controllers/donate.php', true);
    xmlhttp.send(formData);
}

frmDonate.addEventListener('submit', donate);