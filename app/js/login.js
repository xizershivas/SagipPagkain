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
            if (this.status == 200) {
                const response = JSON.parse(this.responseText);
                window.location.href = './dashboard.php';
            } else {
                const response = JSON.parse(this.responseText);
            }
        }
    };

    xmlhttp.open('POST', 'app/controllers/login.php', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/json');
    xmlhttp.send(data);
}

frmLogin.addEventListener('submit', login);