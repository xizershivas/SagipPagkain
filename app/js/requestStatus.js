const requestNo = document.querySelector('#requestNo');
const requestDate = document.querySelector('#requestDate');
const search = document.querySelector('.dt-search input#dt-search-0.dt-input');

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

requestNo.addEventListener('change', getRequestDate);
