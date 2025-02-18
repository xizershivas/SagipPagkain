
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
            const response = JSON.parse(this.responseText);

            if (this.status == 200) {
                // alert(response.data.message);
                window.location.href = 'volunteerSubmitted.php';
            } else {
                alert(response.data.message);
            }
        }
    };
    
    xmlhttp.open('POST', '../../../app/controllers/volunteerForm.php', true);
    xmlhttp.send(formData);
}

btnNext.addEventListener('click', changeTab);
btnPrev.addEventListener('click', changeTab);
volunteerForm.addEventListener('submit', apply);