const beneficiaryRequestForm = document.querySelector('#beneficiaryRequestForm');

async function submitBeneficiaryRequest(e) {
    e.preventDefault();

    const formData = new FormData(this);

    try {
        const res = await fetch('../../../app/controllers/beneficiary.php', {
            method: 'POST',
            body: formData,
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

beneficiaryRequestForm.addEventListener('submit', submitBeneficiaryRequest);