// Common utility functions
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.querySelector('.col-md-9').insertAdjacentElement('afterbegin', alertDiv);
    
    setTimeout(() => alertDiv.remove(), 5000);
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN', { month: '2-digit', year: 'numeric' });
}

function handleCurrentJobCheckbox(checkboxId, endDateInputName) {
    document.getElementById(checkboxId).addEventListener('change', function() {
        const endDateInput = document.querySelector(`input[name="${endDateInputName}"]`);
        endDateInput.disabled = this.checked;
        if (this.checked) endDateInput.value = '';
    });
}