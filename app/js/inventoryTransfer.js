const sourceFoodBankSelect = document.querySelector('#sourceFoodBankSelect');
const targetFoodBankSelect = document.querySelector('#targetFoodBankSelect');
const itemSelect = document.querySelector('#itemSelect');
const availableQty = document.querySelector('#availableQty');
const itemUnit = document.querySelector('#itemUnit');
const transferQty = document.querySelector('#transferQty');
const transferInventoryForm = document.querySelector('#transferInventoryForm');
const itemDetails = {};

function populateItemSelect({ data }) {
    itemSelect.innerHTML = '<option selected disabled value="">-- Select Item --</option>';

    data.forEach(d => {
        const itemSelectOption = document.createElement('option');
        itemSelectOption.value = d.intItemId;
        itemSelectOption.textContent = d.strItem;
        itemSelect.append(itemSelectOption);

        // add item details here
        itemDetails[d.intItemId] = {
            strItem: d.strItem,
            intQuantity: d.intQuantity,
            strUnit: d.strUnit
        };
    });
}

// Get all available items in the selected Food Bank
async function getAvailableItems() {
    try {
        const intFoodBankId = sourceFoodBankSelect.value;
        const res = await fetch(`../../../app/controllers/inventoryTransfer.php?foodBankId=${encodeURIComponent(intFoodBankId)}`, {
            method: 'GET'
        });

        const resData = await res.json();

        if (!res.ok) {
            throw new Error(resData.data.message);
        }

        populateItemSelect(resData);

    } catch (e) {
        alert(e.message);
    }
}

function showItemDetails(e) {
    transferQty.value = 0;
    const intItemId = e.target.value;
    availableQty.value = itemDetails[intItemId].intQuantity;
    itemUnit.value = itemDetails[intItemId].strUnit;
    transferQty.setAttribute('max', itemDetails[intItemId].intQuantity);
}

async function processInventoryTransfer(e) {
    e.preventDefault();

    const formData = new FormData(this);

    try {
        if (sourceFoodBankSelect.value == targetFoodBankSelect.value) {
            alert('Cannot select the same Source and Target Food Bank');
            return;
        }

        const res = await fetch('../../../app/controllers/inventoryTransfer.php', {
            method: 'POST',
            body: formData
        });

        const resData = await res.json();

        if (!res.ok) {
            throw new Error(resData.data.message);
        }

        alert(resData.data.message);
        window.location.reload();
    } catch (e) {
        console.log(e.message);
        alert(e.message);
    }
}

sourceFoodBankSelect.addEventListener('change', getAvailableItems);
itemSelect.addEventListener('change', showItemDetails);
transferInventoryForm.addEventListener('submit', processInventoryTransfer);