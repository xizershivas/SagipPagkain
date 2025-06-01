const btnApproveRequestList = document.getElementsByClassName('btn-approve-req');

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

for (let btnApproveRequest of btnApproveRequestList) {
    btnApproveRequest.addEventListener('click', approveRequest);
}