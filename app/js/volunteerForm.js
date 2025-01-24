
const contactTab = document.querySelector('#nav-contact-tab');
const personalTab = document.querySelector('#nav-personal-tab');
var ctab = new bootstrap.Tab(contactTab);
var ptab = new bootstrap.Tab(personalTab);
const btnNext = document.querySelector('#btnNext');
const btnPrev = document.querySelector('#btnPrev');
const btnApply = document.querySelector('#btnApply');

function changeTab() {
    if (contactTab.classList.contains('active')) {
        ptab.show();
    } else {
        ctab.show();
    }
}

function apply(e) {
    e.preventDefault();
}

btnNext.addEventListener('click', changeTab);
btnPrev.addEventListener('click', changeTab);
btnApply.addEventListener('click', apply);