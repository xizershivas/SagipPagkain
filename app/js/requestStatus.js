const requestNo = document.querySelector('#requestNo');
const requestDate = document.querySelector('#requestDate');

async function getRequestDate(e) {
    debugger;
    const reqId = e.target.value;

    try {
        const res = await fetch(`../../../app/controllers/requestStatus.php?reqId=${reqId}`, {
            method: 'GET'
        });

        const resData = await res.json();

        if (!res.ok) {
            throw new Error(resData.data.message);
        }

        requestDate.value = resData.data;
    } catch (e) {
        alert(e.message);
    }
}

requestNo.addEventListener('change', getRequestDate);
