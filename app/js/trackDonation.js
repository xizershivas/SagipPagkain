// const donorSelect = document.querySelector('#donorSelect');
const donorOptions = document.querySelector('#donorOptions');
const donorItem = document.querySelector('#donorItem');
const foodBankOptions = document.querySelector('#foodBankOptions');
const foodBankItem = document.querySelector('#foodBankItem');
const itemSelect = document.querySelector('#itemSelect');
const beneficiaryOptions = document.querySelector('#beneficiaryOptions');
const beneficiaryItem = document.querySelector('#beneficiaryItem');
const itemQty = document.querySelector('#itemQty');
const unit = document.querySelector('#unit');
const itemSendQty = document.querySelector('#itemSendQty');
const statusSelect = document.querySelector('#statusSelect');
const btnSave = document.querySelector('#btnSave');
let donorId = 0;
let foodBankId = 0;
let beneficiaryId = 0;

function saveTrackDonation() {
    const inputData = {
        intUserId: donorId
        ,intFoodBankId: foodBankId
        ,intItemId: itemSelect.value
        ,intQuantity: itemQty.value
        ,strUnit: unit.value
        ,intSendQuantity: itemSendQty.value
        ,intBeneficiaryId: beneficiaryId
        ,ysnStatus: statusSelect.value
    };

    const xmlhttp = new XMLHttpRequest()

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const { data } = JSON.parse(this.responseText);
            if (this.status == 201) {
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message);
            }
        }
    };

    xmlhttp.open('POST', '../../../app/controllers/trackDonation.php', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/json');
    xmlhttp.send(JSON.stringify(inputData));
}

function getBeneficiary(e) {
    const beneficiaryOptionSelected = document.querySelector(`#beneficiaryOptions option[value="${e.target.value}"]`);
    beneficiaryId = beneficiaryOptionSelected.getAttribute('data-id');
}

itemSendQty.max = parseInt(itemQty.value);
function itemQuantityChange(e) {
    if (isNaN(itemQty.value) || parseInt(itemQty.value) <= 0) {
        itemSendQty.value = 0;
    } else {
        itemSendQty.max = itemQty.value;
        itemSendQty.value = itemSendQty.value++;
        if (parseInt(itemSendQty.value) > parseInt(itemQty.value)) {
            itemSendQty.value = itemQty.value;
        }
    }
}

function getItemQuantity(e) {
    const foodBank = foodBankItem.value;
    const item = e.currentTarget.value;
    itemSendQty.value = 0;
    beneficiaryItem.value = '';
    statusSelect.value = '';
    const xmlhttp = new XMLHttpRequest()

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const { data } = JSON.parse(this.responseText);
            if (this.status == 200) {
                itemQty.value = data.intQuantity ? data.intQuantity : 0;
                unit.value = data.strUnit ? data.strUnit : '';
            } else {
                alert(data.message);
            }
        }
    };

    xmlhttp.open('GET', `../../../app/controllers/trackDonation.php?user=${donorId}&foodBank=${foodBankId}&item=${item}`, true);
    xmlhttp.send();
}

function setItem({ items }) {
    // Item Options
    items.forEach(i => {
        const itemOption = document.createElement('option');
        itemOption.value = i.intItemId;
        itemOption.textContent = i.strItem;
        itemSelect.append(itemOption);
    });
}

function getItem(e) {
    itemSelect.innerHTML = '<option value="">-- Select Item --</option>';
    itemQty.value = 0;
    unit.value = '';
    itemSendQty.value = 0;
    beneficiaryItem.value = '';
    statusSelect.value = '';
    const foodBankOptionSelected = document.querySelector(`#foodBankOptions option[value="${e.target.value}"]`);
    foodBankId = foodBankOptionSelected.getAttribute('data-id');
    const xmlhttp = new XMLHttpRequest()

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const { data } = JSON.parse(this.responseText);
            if (this.status == 200) {
                setItem(data);
            } else {
                // alert(data.message);
            }
        }
    };

    xmlhttp.open('GET', `../../../app/controllers/trackDonation.php?user=${donorId}&foodBank=${foodBankId}`, true);
    xmlhttp.send();
}

function setFoodBank({ foodBanks }) {
    // Food Bank Options
    foodBanks.forEach(fb => {
        const foodBankOption = document.createElement('option');
        foodBankOption.value = fb.strFoodBankName;
        foodBankOption.setAttribute('data-id', fb.intFoodBankDetailId);
        foodBankOptions.append(foodBankOption);
    });
}

function getFoodBank(e) {
    foodBankOptions.innerHTML = '';
    foodBankItem.value = '';
    itemSelect.innerHTML = '<option value="">-- Select Item --</option>';
    itemQty.value = 0;
    unit.value = '';
    itemSendQty.value = 0;
    beneficiaryItem.value = '';
    statusSelect.value = '';
    const donorOptionSelected = document.querySelector(`#donorOptions option[value="${e.target.value}"]`);
    donorId = donorOptionSelected.getAttribute('data-id');
    const xmlhttp = new XMLHttpRequest()

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const { data } = JSON.parse(this.responseText);
            if (this.status == 200) {
                setFoodBank(data);
            } else {
                alert(data.message);
            }
        }
    };

    xmlhttp.open('GET', `../../../app/controllers/trackDonation.php?user=${donorId}`, true);
    xmlhttp.send();
}

function setData({ donors, /*foodBanks,*/ beneficiaries}) {
    // Donor Options
    donors.forEach(d => {
        const donorOption = document.createElement('option');
        donorOption.value = d.strFullName;
        donorOption.setAttribute('data-id', d.intUserId);
        donorOptions.append(donorOption);
    });

    // Beneficiary Options
    beneficiaries.forEach(b => {
        const beneficiaryOption = document.createElement('option');
        beneficiaryOption.value = b.strName;
        beneficiaryOption.setAttribute('data-id', b.intBeneficiaryId);
        beneficiaryOptions.append(beneficiaryOption);
    });
}

function loadData(e) {
    const xmlhttp = new XMLHttpRequest()

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const { data } = JSON.parse(this.responseText);
            if (this.status == 200) {
                setData(data);
            } else {
                // alert(data.message);
            }
        }
    };

    xmlhttp.open('GET', '../../../app/controllers/trackDonation.php', true);
    xmlhttp.send();
}

window.addEventListener('load', loadData);
donorItem.addEventListener('change', getFoodBank);
foodBankItem.addEventListener('change', getItem);
itemSelect.addEventListener('change', getItemQuantity);
itemSendQty.addEventListener('change', itemQuantityChange);
itemSendQty.addEventListener('input', itemQuantityChange);
beneficiaryItem.addEventListener('input', getBeneficiary);
btnSave.addEventListener('click', saveTrackDonation);