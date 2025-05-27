const frmLogin = document.querySelector('#frmLogin');
const username = document.querySelector('#username');
const password = document.querySelector('#password');

function login(e) {
    e.preventDefault();

    const data = JSON.stringify({
        username: username.value,
        password: password.value
    });

    const xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const response = JSON.parse(this.responseText);

            if (this.status == 200) {
                if (response.data.ysnAdmin) {
                    window.location.href = 'src/web/app/dashboard.php';
                } else if (response.data.ysnDonor) {
                    window.location.href = 'src/web/donor/dashboard.php';
                } else if (response.data.ysnPartner) {
                    window.location.href = 'src/web/ngo/dashboard.php';
                } else if (response.data.ysnBeneficiary) {
                    window.location.href = 'src/web/beneficiary/assistanceRequest.php';
                } else if (response.data.ysnStaff) {
                    window.location.href = 'src/web/app/dashboard.php';
                }
            } else {
                alert(response.data.message);
            }
        }
    };

    xmlhttp.open('POST', 'app/controllers/login.php', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/json');
    xmlhttp.send(data);
}

frmLogin.addEventListener('submit', login);