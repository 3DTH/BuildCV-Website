function submitCertificate() {
    const form = document.getElementById('certificateForm');
    const formData = new FormData(form);
    
    fetch(`${BASE_URL}/certificate/add`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('certificatesList').innerHTML = renderCertificates(data.data);
            bootstrap.Modal.getInstance(document.getElementById('certificateModal')).hide();
            form.reset();
            showAlert('success', 'Thêm chứng chỉ thành công!');
        } else {
            showAlert('danger', data.error);
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi thêm chứng chỉ!'));
}

function editCertificate(id) {
    fetch(`${BASE_URL}/certificate/get/${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const certificate = data.data;
            const form = document.getElementById('certificateForm');
            
            form.querySelector('input[name="id"]').value = certificate.id;
            form.querySelector('input[name="name"]').value = certificate.name;
            form.querySelector('input[name="issuer"]').value = certificate.issuer;
            form.querySelector('input[name="issue_date"]').value = certificate.issue_date;
            form.querySelector('input[name="expiry_date"]').value = certificate.expiry_date || '';
            form.querySelector('textarea[name="description"]').value = certificate.description;
            
            document.querySelector('#certificateModal .modal-title').textContent = 'Chỉnh sửa chứng chỉ';
            document.querySelector('#certificateModal .btn-primary').setAttribute('onclick', 'updateCertificate()');
            
            const modal = new bootstrap.Modal(document.getElementById('certificateModal'));
            modal.show();
        } else {
            showAlert('danger', data.error);
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi tải thông tin chứng chỉ!'));
}

function updateCertificate() {
    if (!validateCertificateDates()) return;
    
    const form = document.getElementById('certificateForm');
    const formData = new FormData(form);
    const id = formData.get('id');
    
    fetch(`${BASE_URL}/certificate/update/${id}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('certificatesList').innerHTML = renderCertificates(data.data);
            bootstrap.Modal.getInstance(document.getElementById('certificateModal')).hide();
            resetCertificateForm();
            showAlert('success', 'Cập nhật chứng chỉ thành công!');
        } else {
            showAlert('danger', data.error);
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi cập nhật chứng chỉ!'));
}

function deleteCertificate(id) {
    if (confirm('Bạn có chắc chắn muốn xóa chứng chỉ này?')) {
        fetch(`${BASE_URL}/certificate/delete/${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`#certificatesList [data-id="${id}"]`).remove();
                showAlert('success', 'Xóa chứng chỉ thành công!');
            } else {
                showAlert('danger', data.error);
            }
        })
        .catch(error => showAlert('danger', 'Có lỗi xảy ra khi xóa chứng chỉ!'));
    }
}

function renderCertificates(certificates) {
    return certificates.map(cert => `
        <div class="certificate-item border-bottom pb-3 mb-3" data-id="${cert.id}">
            <div class="d-flex justify-content-between">
                <h6 class="mb-1">${cert.name}</h6>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-primary" 
                        onclick="editCertificate(${cert.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" 
                        onclick="deleteCertificate(${cert.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <p class="mb-1">${cert.issuer}</p>
            <p class="text-muted small mb-1">
                ${formatDate(cert.issue_date)}
                ${cert.expiry_date ? ' - ' + formatDate(cert.expiry_date) : ''}
            </p>
            <p class="mb-0">${cert.description}</p>
        </div>
    `).join('');
}

function resetCertificateForm() {
    const form = document.getElementById('certificateForm');
    form.reset();
    form.querySelector('input[name="id"]').value = '';
    document.querySelector('#certificateModal .modal-title').textContent = 'Thêm chứng chỉ';
    document.querySelector('#certificateModal .btn-primary').setAttribute('onclick', 'submitCertificate()');
}

// Add event listener for modal close
document.getElementById('certificateModal').addEventListener('hidden.bs.modal', resetCertificateForm);

function validateCertificateDates() {
    const issueDate = document.querySelector('#certificateForm input[name="issue_date"]').value;
    const expiryDate = document.querySelector('#certificateForm input[name="expiry_date"]').value;
    
    // Check if issue date is in future
    if (new Date(issueDate) > new Date()) {
        showAlert('danger', 'Ngày cấp không thể sau ngày hiện tại', 'certificate');
        return false;
    }
    
    // Check if expiry date is before issue date
    if (expiryDate && new Date(expiryDate) < new Date(issueDate)) {
        showAlert('danger', 'Ngày hết hạn không thể trước ngày cấp', 'certificate');
        return false;
    }
    
    return true;
}

// Add event listeners for date inputs
document.addEventListener('DOMContentLoaded', function() {
    const issueDateInput = document.querySelector('#certificateForm input[name="issue_date"]');
    const expiryDateInput = document.querySelector('#certificateForm input[name="expiry_date"]');
    
    if (issueDateInput && expiryDateInput) {
        // Set max date for issue date to today
        const today = new Date().toISOString().split('T')[0];
        issueDateInput.max = today;
        
        // Update min date for expiry date when issue date changes
        issueDateInput.addEventListener('change', function() {
            expiryDateInput.min = this.value;
            validateCertificateDates();
        });
        
        expiryDateInput.addEventListener('change', validateCertificateDates);
    }
});