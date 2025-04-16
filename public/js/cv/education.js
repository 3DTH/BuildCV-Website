// Add this function at the top of the file
function validateEducationDates() {
    const startDate = document.querySelector('#educationForm input[name="start_date"]').value;
    const endDate = document.querySelector('#educationForm input[name="end_date"]').value;
    
    if (endDate && new Date(endDate) < new Date(startDate)) {
        showAlert('danger', 'Ngày kết thúc không thể trước ngày bắt đầu', 'education');
        return false;
    }
    return true;
}

function submitEducation() {
    if (!validateEducationDates()) return;
    
    const form = document.getElementById('educationForm');
    const formData = new FormData(form);
    
    fetch(`${BASE_URL}/education/add`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('educationList').innerHTML = renderEducations(data.data);
            bootstrap.Modal.getInstance(document.getElementById('educationModal')).hide();
            showAlert('success', 'Thêm học vấn thành công!', 'education');
            resetEducationForm();
        } else {
            showAlert('danger', data.error, 'education');
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi thêm học vấn!', 'education'));
}

function editEducation(id) {
    fetch(`${BASE_URL}/education/get/${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const education = data.data;
            const form = document.getElementById('educationForm');
            
            form.querySelector('input[name="id"]').value = education.id;
            form.querySelector('input[name="cv_id"]').value = education.cv_id;
            form.querySelector('input[name="institution"]').value = education.institution;
            form.querySelector('input[name="degree"]').value = education.degree;
            form.querySelector('input[name="field_of_study"]').value = education.field_of_study;
            form.querySelector('input[name="start_date"]').value = education.start_date;
            form.querySelector('input[name="end_date"]').value = education.end_date || '';
            form.querySelector('textarea[name="description"]').value = education.description || '';
            
            document.querySelector('#educationModal .modal-title').textContent = 'Chỉnh sửa học vấn';
            const submitButton = document.querySelector('#educationModal .btn-primary');
            submitButton.textContent = 'Cập nhật';
            submitButton.onclick = updateEducation;
            
            const modal = new bootstrap.Modal(document.getElementById('educationModal'));
            modal.show();
        } else {
            showAlert('danger', data.error || 'Không thể tải thông tin học vấn');
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi tải thông tin học vấn!'));
}

function updateEducation() {
    if (!validateEducationDates()) return;
    
    const form = document.getElementById('educationForm');
    const formData = new FormData(form);
    const id = formData.get('id');
    
    fetch(`${BASE_URL}/education/update/${id}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('educationList').innerHTML = renderEducations(data.data);
            bootstrap.Modal.getInstance(document.getElementById('educationModal')).hide();
            showAlert('success', 'Cập nhật học vấn thành công!', 'education');
            resetEducationForm();
        } else {
            showAlert('danger', data.error || 'Không thể cập nhật học vấn');
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi cập nhật học vấn!'));
}

function deleteEducation(id) {
    if (confirm('Bạn có chắc chắn muốn xóa học vấn này?')) {
        fetch(`${BASE_URL}/education/delete/${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const element = document.querySelector(`.education-item[data-id="${id}"]`);
                if (element) {
                    element.remove();
                }
                showAlert('success', 'Xóa học vấn thành công!', 'education');
            } else {
                throw new Error(data.error || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Có lỗi xảy ra khi xóa học vấn!', 'education');
        });
    }
}

function resetEducationForm() {
    const form = document.getElementById('educationForm');
    form.reset();
    form.querySelector('input[name="id"]').value = '';
    document.querySelector('#educationModal .modal-title').textContent = 'Thêm học vấn';
    const submitButton = document.querySelector('#educationModal .btn-primary');
    submitButton.textContent = 'Lưu';
    submitButton.onclick = submitEducation;
}

function renderEducations(educations) {
    return educations.map(edu => `
        <div class="education-item border-bottom pb-3 mb-3" data-id="${edu.id}">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="mb-1">${edu.institution}</h6>
                    <p class="mb-1">${edu.degree} - ${edu.field_of_study}</p>
                    <p class="text-muted small mb-1">
                        ${formatDate(edu.start_date)} -
                        ${edu.end_date ? formatDate(edu.end_date) : 'Hiện tại'}
                    </p>
                    ${edu.description ? `<p class="mb-0">${edu.description}</p>` : ''}
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editEducation(${edu.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteEducation(${edu.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Add event listener for modal close
document.getElementById('educationModal').addEventListener('hidden.bs.modal', resetEducationForm);

// Add event listeners for date inputs
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.querySelector('#educationForm input[name="start_date"]');
    const endDateInput = document.querySelector('#educationForm input[name="end_date"]');
    
    if (startDateInput && endDateInput) {
        endDateInput.addEventListener('change', validateEducationDates);
        startDateInput.addEventListener('change', validateEducationDates);
    }
});