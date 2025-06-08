const btnApproveRequestList = document.getElementsByClassName('btn-approve-req');
const btnRejectRequestList = document.getElementsByClassName('btn-reject-req');
const btnReadyRequestList = document.getElementsByClassName('btn-ready-req');

async function approveRequest(e) {
    if (!confirm("Are you sure you want to approve this request?")) return;

    let requestId = e.currentTarget.getAttribute('data-id');

    try {
      const res = await fetch('../../../app/controllers/requestApproval.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ requestId })
      });

      const resData = await res.json();

      if (!res.ok) {
        throw new Error(resData.data.message);
      }

      alert(resData.data.message);
      window.location.reload();
    } catch (e) {
      alert(e.message);
    }
}

async function rejectRequest(e) {
    if (!confirm("Are you sure you want to reject this request?")) return;

    let requestId = e.currentTarget.getAttribute('data-id');
    let isReject = 1;

    try {
      const res = await fetch('../../../app/controllers/requestApproval.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ requestId, isReject })
      });

      const resData = await res.json();

      if (!res.ok) {
        throw new Error(resData.data.message);
      }

      alert(resData.data.message);
      window.location.reload();
    } catch (e) {
      alert(e.message);
    }
}

async function readyRequest(e) {
    if (!confirm("Are you sure you want to set this request for pickup?")) return;

    let requestId = e.currentTarget.getAttribute('data-id');
    let isPickup = 1;

    try {
      const res = await fetch('../../../app/controllers/requestApproval.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ requestId, isPickup })
      });

      const resData = await res.json();

      if (!res.ok) {
        throw new Error(resData.data.message);
      }

      alert(resData.data.message);
      window.location.reload();
    } catch (e) {
      alert(e.message);
    }
}

for (let btnApproveRequest of btnApproveRequestList) {
    btnApproveRequest.addEventListener('click', approveRequest);
}

for (let btnRejectRequest of btnRejectRequestList) {
    btnRejectRequest.addEventListener('click', rejectRequest);
}

for (let btnReadyRequest of btnReadyRequestList) {
    btnReadyRequest.addEventListener('click', readyRequest);
}