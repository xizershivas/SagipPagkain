
const contactTab = document.querySelector('#nav-contact-tab');
const personalTab = document.querySelector('#nav-personal-tab');
var ctab = new bootstrap.Tab(contactTab);
var ptab = new bootstrap.Tab(personalTab);
const btnNext = document.querySelector('#btnNext');
const btnPrev = document.querySelector('#btnPrev');
const volunteerForm = document.querySelector('#volunteerForm');

function changeTab() {
    if (contactTab.classList.contains('active')) {
        ptab.show();
    } else {
        ctab.show();
    }
}

function apply(e) {
    e.preventDefault();

    const formData = new FormData(this);

    const xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) {
                alert(JSON.parse(this.responseText).message);
                window.location.href = 'volunteerForm.php';
            } else {
                alert(JSON.parse(this.responseText).message);
            }
        }
    };
    
    xmlhttp.open('POST', 'app/controllers/volunteerForm.php', true);
    xmlhttp.send(formData);
}

btnNext.addEventListener('click', changeTab);
btnPrev.addEventListener('click', changeTab);
volunteerForm.addEventListener('submit', apply);