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
 
            tdRowNo.textContent = index;
            tdItem.textContent = d.strItem;
            tdQuantity.textContent = d.intQuantity;
            tdUnit.textContent = d.strUnit;
            tdCategory.textContent = d.strCategory;
            tdExpiDate.textContent = d.dtmExpirationDate;
            tdFoodBank.textContent = d.strFoodBank;
 
            tr.append(tdRowNo);
            tr.append(tdItem);
            tr.append(tdQuantity);
            tr.append(tdUnit);
            tr.append(tdCategory);
            tr.append(tdExpiDate);
            tr.append(tdFoodBank);
 
            tableBody.append(tr);
        });
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