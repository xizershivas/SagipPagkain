const requestNo = document.querySelector('#requestNo');
const requestDate = document.querySelector('#requestDate');
const search = document.querySelector('.dt-search input#dt-search-0.dt-input');
const btnDeleteList = document.getElementsByClassName('btn-delete-req');

let dataTable;

document.addEventListener('DOMContentLoaded', function () {
  // Initialize DataTable and save reference
  dataTable = new DataTable('#requestTrackDataTable', {
    lengthMenu: [5, 10, 25, 50, 100]
  });
});

async function getRequestDate(e) {
    const reqId = e.target.value;
    let selectedText = e.target.options[e.target.selectedIndex].text;

    if (!reqId) {
        selectedText = '';
        dataTable.search(selectedText).draw();
        return;
    }

    try {
        const res = await fetch(`../../../app/controllers/requestStatus.php?reqId=${reqId}`, {
            method: 'GET'
        });

        const resData = await res.json();

        if (!res.ok) {
            throw new Error(resData.data.message);
        }

        requestDate.value = resData.data;
        dataTable.search(selectedText).draw();
    } catch (e) {
        alert(e.message);
    }
}

async function deleteBeneficiaryRequest(e) {
    const intBeneficiaryRequestId = parseInt(e.currentTarget.getAttribute('data-id'));

    if (!confirm("Are you sure you want to delete this request?")) return;

    try {
        const res = await fetch(`../../../app/controllers/requestStatus.php`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ intBeneficiaryRequestId })
        });

        const resData = await res.json();

        if (!res.ok) {
            throw new Error(resData.data.message);
        }

        alert('Successfully deleted');
        window.location.reload();
    } catch (e) {
        alert(e.message);
    }
}

requestNo.addEventListener('change', getRequestDate);

for (let btnDelete of btnDeleteList) {
    btnDelete.addEventListener('click', deleteBeneficiaryRequest);
}