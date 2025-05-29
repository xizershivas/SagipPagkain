const filterBy = document.querySelector('#filterBy');
const searchItem = document.querySelector("#searchItem");
const filterOptions = document.querySelector('#filterOptions');
const tableBody = document.querySelector('#tableBody');
 
// Show inventory table data
function setTableData({ inventoryData }) {
    tableBody.innerHTML = '';
    if (!inventoryData) {
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.textContent = "No records found";
        td.setAttribute('colspan', 7);
        td.style.textAlign = 'center';
        tr.append(td);
        tableBody.append(tr);
    }
    else {
        inventoryData.forEach((d, index) => {
            index++;
 
            const tr = document.createElement('tr');
            const tdRowNo = document.createElement('td');
            const tdItem = document.createElement('td');
            const tdQuantity = document.createElement('td');
            const tdUnit = document.createElement('td');
            const tdCategory = document.createElement('td');
            const tdExpiDate = document.createElement('td');
            const tdFoodBank = document.createElement('td');
            const tdTransfer = document.createElement('td');
 
            tdRowNo.textContent = index;
            tdItem.textContent = d.strItem;
            tdQuantity.textContent = d.intQuantity;
            tdUnit.textContent = d.strUnit;
            tdCategory.textContent = d.strCategory;
            tdExpiDate.textContent = d.dtmExpirationDate;
            tdFoodBank.textContent = d.strFoodBank;
            tdTransfer.innerHTML = `<a href='javascript:void(0)' class="btn-transfer" title='Transfer Item' 
                data-intInventoryId=${d.intInventoryId} 
                data-intFoodBankId=${d.intFoodBankId} 
                data-intItemId=${d.intItemId} 
                data-intQuantity=${d.intQuantity} 
                data-intUnitId=${d.intUnitId} 
                data-strUnit=${d.strUnit} 
                data-intCategoryId=${d.intCategoryId} 
                data-strCategory=${d.strCategory} 
                data-dtmExpirationDate="${d.dtmExpirationDate}" 
                data-bs-toggle='modal' 
                data-bs-target='#transferInventoryModal'>
                <i class='bi bi-arrow-right-square-fill'></i></a>`;
            
            tr.append(tdRowNo);
            tr.append(tdItem);
            tr.append(tdQuantity);
            tr.append(tdUnit);
            tr.append(tdCategory);
            tr.append(tdExpiDate);
            tr.append(tdFoodBank);
            tr.append(tdTransfer);
 
            tableBody.append(tr);
        });

        const btnTransferList = document.getElementsByClassName('btn-transfer');
        for (let btnTransfer of btnTransferList) {
            btnTransfer.addEventListener('click', setInventoryTransferData);
        }
    }
}
 
function inputSearch(e) {
    let search = e ? e.currentTarget.value : "";
    let filter = filterBy.value;
 
    const xmlhttp = new XMLHttpRequest();
 
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const response = JSON.parse(this.responseText);
            if (this.status == 200 || this.status == 202) {
                setDataListOptions(response.data, filter);
                setTableData(response.data);
            } else {
 
            }
        }
    };
 
    xmlhttp.open('GET', `../../../app/controllers/inventoryManagement.php?filter=${filter}&search=${search}`, true);
    xmlhttp.send(); 
}
 
function setDataListOptions({ dataListOptions }, filter) {
    filterOptions.innerHTML = '';
 
    dataListOptions.forEach(r => {
        const option = document.createElement('option');
        switch(filter) {
            case 'strItem': option.value = r.strItem; break;
            case 'strUnit': option.value = r.strUnit; break;
            case 'strFoodBank': option.value = r.strFoodBank; break;
            default: option.value = r.strCategory; break;
        }
        filterOptions.append(option);
    });
}
 
function filterOption(e) {
    searchItem.value = '';
    let filter = e ? e.currentTarget.value : "strCategory";
    const xmlhttp = new XMLHttpRequest();
 
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            const response = JSON.parse(this.responseText);
            if (this.status == 200) {
                setDataListOptions(response.data, filter);
                setTableData(response.data);
            } else {
 
            }
        }
    };
 
    xmlhttp.open('GET', `../../../app/controllers/inventoryManagement.php?filter=${filter}`, true);
    xmlhttp.send();
}

function setSelectedItem(intItemId) {
    itemSelect.value = intItemId;
}

function setInventoryTransferData(e) {
    const {
        intinventoryid,
        intfoodbankid,
        intitemid,
        intquantity,
        intunitid,
        intcategoryid,
        dtmexpirationdate
    } = e.currentTarget.dataset;

    const d = new Date(dtmexpirationdate);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const date = String(d.getDate()).padStart(2, '0');

    inventoryId.value = intinventoryid;
    sourceFoodBankSelect.value = intfoodbankid;
    itemSelect.value = intitemid;
    availableQty.value = intquantity;
    unitSelect.value = intunitid;
    categorySelect.value = intcategoryid;
    expirationDate.value = `${year}-${month}-${date}`;
    transferQty.setAttribute('max', intquantity);
}
 
filterBy.addEventListener('change', filterOption);
searchItem.addEventListener('input', inputSearch);
searchItem.addEventListener('keydown', (e) => {
    if (e.key === "Enter") {
        e.preventDefault();
    }
});
 
window.onload = function() {
    filterOption(); // triggered on page load to populate datalist
}