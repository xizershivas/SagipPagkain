const donorSelect = document.querySelector('#donorSelect');
const foodBankOptions = document.querySelector('#foodBankOptions');
const foodBankItem = document.querySelector('#foodBankItem');
const itemSelect = document.querySelector('#itemSelect');
const beneficiaryOptions = document.querySelector('#beneficiaryOptions');
const beneficiaryItem = document.querySelector('#beneficiaryItem');
const itemQty = document.querySelector('#itemQty');
const unit = document.querySelector('#unit');
const itemQtySend = document.querySelector('#itemQtySend');
const statusSelect = document.querySelector('#statusSelect');

itemQtySend.max = parseInt(itemQty.value);

function itemQuantityChange(e) {
    if (isNaN(itemQty.value) || parseInt(itemQty.value) <= 0) {
        itemQtySend.value = 0;
    } else {
        itemQtySend.max = itemQty.value;
        itemQtySend.value = parseInt(itemQtySend.value)++;
    }
}

function getItemQuantity(e) {
    const user = donorSelect.value;
    const foodBank = foodBankItem.value;
    const item = e.currentTarget.value;

    const xmlhttp = new XMLHttpRequest()

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const { data } = JSON.parse(this.responseText);
            if (this.status == 200) {
                itemQty.value = data.intQuantity
                unit.value = data.strUnit
            } else {
                alert(data.message);
            }
        }
    };

    xmlhttp.open('GET', `../../../app/controllers/trackDonation.php?user=${user}&foodBank=${foodBank}&item=${item}`, true);
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
    let user = donorSelect.value;
    let foodBank = e.currentTarget.value;
    const xmlhttp = new XMLHttpRequest()

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const { data } = JSON.parse(this.responseText);
            if (this.status == 200) {
                setItem(data);
            } else {
                alert(data.message);
            }
        }
    };

    xmlhttp.open('GET', `../../../app/controllers/trackDonation.php?user=${user}&foodBank=${foodBank}`, true);
    xmlhttp.send();
}

function setFoodBank({ foodBanks }) {
    // Food Bank Options
    foodBanks.forEach(fb => {
        const foodBankOption = document.createElement('option');
        foodBankOption.value = fb.strFoodBank;
        foodBankOptions.append(foodBankOption);
    });
}

function getFoodBank(e) {
    foodBankOptions.innerHTML = '';
    foodBankItem.value = '';
    itemSelect.innerHTML = '<option value="">-- Select Item --</option>';
    itemQty.value = 0;
    unit.value = '';
    itemQtySend.value = 0;
    beneficiaryItem.value = '';
    statusSelect.innerHTML = '<option value="">-- Set Status --</option>'
        + '<option value="1">Received</option>'
        + '<option value="0">In Transit</option>';
    let intUserId = e.currentTarget.value;
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

    xmlhttp.open('GET', `../../../app/controllers/trackDonation.php?user=${intUserId}`, true);
    xmlhttp.send();
}

function setData({ donors, /*foodBanks,*/ beneficiaries}) {
    // Donor Options
    donors.forEach(d => {
        const donorOption = document.createElement('option');
        donorOption.value = d.intUserId;
        donorOption.textContent = d.strDonorName;
        donorSelect.append(donorOption);
    });

    // Beneficiary Options
    beneficiaries.forEach(b => {
        const beneficiaryOption = document.createElement('option');
        beneficiaryOption.value = b.strName;
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
                alert(data.message);
            }
        }
    };

    xmlhttp.open('GET', '../../../app/controllers/trackDonation.php', true);
    xmlhttp.send();
}

window.addEventListener('load', loadData);
donorSelect.addEventListener('change', getFoodBank);
foodBankItem.addEventListener('input', getItem);
itemSelect.addEventListener('change', getItemQuantity);
itemQtySend.addEventListener('change', itemQuantityChange);