const frmDonate = document.querySelector("#frmDonate");
const itemSelect = document.querySelector("#itemSelect");
const unitSelect = document.querySelector("#unitSelect");
const categorySelect = document.querySelector("#categorySelect");
const foodbankNameSelect = document.querySelector("#foodbankNameSelect");

function donate(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const xmlhttp = new XMLHttpRequest();

    if (!this.elements.date.value) {
        alert('Invalid Date');
    } else {
        if (this.elements.verification.files.length > 2) {
            alert('You can only select 2 files in total');
        } else {
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4) {
                    const response = JSON.parse(this.responseText);

                    if (this.status == 200 || this.status == 201) {
                        alert(response.data.message);
                        window.location.reload();
                    } else {
                        alert(response.data.message);
                    }
                }
            };
        }

        xmlhttp.open('POST', '../../../app/controllers/donate.php', true);
        xmlhttp.send(formData);
    }
}

async function getItemDetails(e) {
    const itemId = e.target.value

    try {
        const res = await fetch(`../../../app/controllers/donate.php?itemId=${itemId}`, {
            method: 'GET'
        });

        const resData = await res.json();

        if (!res.ok) {
            throw new Error(resData.data.message);
        }

        unitSelect.value = resData.data.intUnitId;
        categorySelect.value = resData.data.intCategoryId;
    } catch (e) {
        alert(e.message);
    }
}

async function getFoodbank(e) {
    const itemId = e.target.value

    try {
        const res = await fetch(`../../../app/controllers/donate.php?itemId=${itemId}&ex=1`, {
            method: 'GET'
        });

        const resData = await res.json();

        if (!res.ok) {
            throw new Error(resData.data.message);
        }

        foodbankNameSelect.value = resData.data.intFoodBankDetailId;
    } catch (e) {
        alert(e.message);
    }
}

frmDonate.addEventListener('submit', donate);
itemSelect.addEventListener('change', getItemDetails);
itemSelect.addEventListener('change', getFoodbank);