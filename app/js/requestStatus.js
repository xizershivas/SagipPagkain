const requestNo = document.querySelector('#requestNo');
const requestDate = document.querySelector('#requestDate');
const search = document.querySelector('.dt-search input#dt-search-0.dt-input');
const btnDeleteList = document.getElementsByClassName('btn-delete-req');
const stepper = document.querySelector("#stepper");
const btnSubmitForApproval = document.querySelector("#btnSubmitForApproval");

let dataTable;

document.addEventListener('DOMContentLoaded', function () {
  // Initialize DataTable and save reference
  dataTable = new DataTable('#requestTrackDataTable', {
    lengthMenu: [5, 10, 25, 50, 100]
  });
});

function updateUIRequestStatus({ data }) {
    stepper.innerHTML = '';
    const intApproved = data.intApproved;
    const ysnSubmitted = data.ysnSubmitted;

    const steps = [
      { label: "Request Created" },
      { label: intApproved === 0 || intApproved === 1 ? "Waiting for Approval" : "Rejected" },
      { label: "Approved - Ready for Pick Up" }
    ];

    steps.forEach((step, index) => {
      const stepNumber = index + 1;
      const isLast = stepNumber === steps.length;
      let circleClass = "";
      let lineClass = "";

      if (step.label === "Request Created") {
        if (intApproved === 2) 
            circleClass = "step-complete"
        else
            circleClass = (intApproved === 0 && ysnSubmitted === 0) || (intApproved === 0 && ysnSubmitted === 1) || (intApproved === 1 && ysnSubmitted === 1) ? "step-complete" : "step-pending";
      }
      else if (step.label === "Waiting for Approval") {
        circleClass = (intApproved === 0 && ysnSubmitted === 1) || (intApproved === 1 && ysnSubmitted === 1) ? "step-complete" : "step-pending";
      }
      else {
        circleClass = intApproved === 1 && ysnSubmitted === 1 ? "step-complete" : "step-pending";
      }

      if (step.label === "Request Created" && intApproved === 2) {
        lineClass = "line-complete";
      } else {
        if (step.label === "Request Created" && intApproved === 0 && ysnSubmitted === 1) {
            lineClass = "line-complete";
        }
        else if ((step.label === "Request Created" || step.label === "Waiting for Approval") && intApproved === 1 && ysnSubmitted === 1) {
            lineClass = "line-complete";
        }
        else {
            lineClass = "line-pending";
        }
    }
    
      // Step circle
      const stepHTML = `
        <div class="mb-4 mb-md-0 d-flex flex-column align-items-center">
          <div class="step-circle ${circleClass}">
            ${stepNumber}
          </div>
          <div class="step-label">${step.label}</div>
          <div class="step-date">${step.status === "approved" ? step.date : ''}</div>
        </div>
      `;

      stepper.insertAdjacentHTML("beforeend", stepHTML);

      // Line (except after last)
		if (!isLast) {
		  stepper.insertAdjacentHTML(
			"beforeend",
			`
			<div class="d-none d-md-flex align-items-center flex-grow-1 px-2">
			  <div class="w-100 step-line ${lineClass}"></div>
			</div>
			`
		  );
		}

    });
}

async function getRequestDetails(e) {
    const reqId = e.target.value;
    let selectedText = e.target.options[e.target.selectedIndex].text;

    if (!reqId) {
        selectedText = '';
        dataTable.search(selectedText).draw();
        stepper.innerHTML = '';
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

        requestDate.value = resData.data.dtmCreatedDate;
        dataTable.search(selectedText).draw();
        updateUIRequestStatus(resData);

        if (!resData.data.ysnSubmitted) {
            btnSubmitForApproval.classList.remove('d-none');
        } else {
            btnSubmitForApproval.classList.add('d-none');
        }
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

async function submitForApproval() {
    if (!confirm("Are you sure you want to submit this for approval?")) return;

    const requestId = requestNo.value;

    try {
      const res = await fetch('../../../app/controllers/requestStatus.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ requestId })
      });

      const resData = await res.json();

      alert(resData.data.message);
      window.location.reload();
    } catch (e) {
      alert(e.message);
    }
}

requestNo.addEventListener('change', getRequestDetails);

for (let btnDelete of btnDeleteList) {
    btnDelete.addEventListener('click', deleteBeneficiaryRequest);
}

btnSubmitForApproval.addEventListener('click', submitForApproval);