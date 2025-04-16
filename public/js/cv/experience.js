function submitExperience() {
    const form = document.getElementById('experienceForm');
    const formData = new FormData(form);
    
    // Handle is_current checkbox properly
    const currentJobChecked = form.querySelector('input[name="is_current"]').checked;
    formData.set('is_current', currentJobChecked ? 'true' : 'false');
    
    // Clear end_date if current job is checked
    if (currentJobChecked) {
        formData.set('end_date', '');
    }

    fetch(`${BASE_URL}/experience/add`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('experienceList').innerHTML = renderExperiences(data.data);
            bootstrap.Modal.getInstance(document.getElementById('experienceModal')).hide();
            showAlert('success', 'Thêm kinh nghiệm làm việc thành công!');
            resetExperienceForm();
        } else {
            showAlert('danger', data.error || 'Không thể thêm kinh nghiệm làm việc');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Có lỗi xảy ra khi thêm kinh nghiệm làm việc!');
    });
}



// Update the editExperience function
function editExperience(id) {
    fetch(`${BASE_URL}/experience/get/${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const experience = data.data;
            const form = document.getElementById('experienceForm');
            
            form.querySelector('input[name="id"]').value = experience.id;
            form.querySelector('input[name="cv_id"]').value = experience.cv_id;
            form.querySelector('input[name="position"]').value = experience.position;
            form.querySelector('input[name="company"]').value = experience.company;
            form.querySelector('input[name="start_date"]').value = experience.start_date;
            
            const endDateInput = form.querySelector('input[name="end_date"]');
            const isCurrentCheckbox = form.querySelector('input[name="is_current"]');
            
            isCurrentCheckbox.checked = experience.is_current == 1;
            endDateInput.value = experience.end_date || '';
            endDateInput.disabled = isCurrentCheckbox.checked;
            
            form.querySelector('textarea[name="description"]').value = experience.description || '';
            
            document.querySelector('#experienceModal .modal-title').textContent = 'Chỉnh sửa kinh nghiệm làm việc';
            const submitButton = document.querySelector('#experienceModal .btn-primary');
            submitButton.textContent = 'Cập nhật';
            submitButton.onclick = updateExperience;
            
            const modal = new bootstrap.Modal(document.getElementById('experienceModal'));
            modal.show();
        } else {
            showAlert('danger', data.error || 'Không thể tải thông tin kinh nghiệm làm việc', 'experience');
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi tải thông tin kinh nghiệm làm việc!', 'experience'));
}

function updateExperience() {
    const form = document.getElementById('experienceForm');
    const formData = new FormData(form);
    const id = formData.get('id');
    
    // Handle is_current checkbox properly
    const currentJobChecked = form.querySelector('input[name="is_current"]').checked;
    formData.set('is_current', currentJobChecked ? 'true' : 'false');
    
    // Clear end_date if current job is checked
    if (currentJobChecked) {
        formData.set('end_date', '');
    }

    fetch(`${BASE_URL}/experience/update/${id}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('experienceList').innerHTML = renderExperiences(data.data);
            bootstrap.Modal.getInstance(document.getElementById('experienceModal')).hide();
            showAlert('success', 'Cập nhật kinh nghiệm làm việc thành công!', 'experience');
            resetExperienceForm();
        } else {
            showAlert('danger', data.error || 'Không thể cập nhật kinh nghiệm làm việc', 'experience');
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi cập nhật kinh nghiệm làm việc!', 'experience'));
}

function deleteExperience(id) {
    if (confirm('Bạn có chắc chắn muốn xóa kinh nghiệm làm việc này?')) {
        fetch(`${BASE_URL}/experience/delete/${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const element = document.querySelector(`.experience-item[data-id="${id}"]`);
                if (element) {
                    element.remove();
                }
                showAlert('success', 'Xóa kinh nghiệm làm việc thành công!', 'experience');
            } else {
                throw new Error(data.error || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Có lỗi xảy ra khi xóa kinh nghiệm làm việc!', 'experience');
        });
    }
}

function resetExperienceForm() {
    const form = document.getElementById('experienceForm');
    form.reset();
    form.querySelector('input[name="id"]').value = '';
    document.querySelector('#experienceModal .modal-title').textContent = 'Thêm kinh nghiệm làm việc';
    const submitButton = document.querySelector('#experienceModal .btn-primary');
    submitButton.textContent = 'Lưu';
    submitButton.onclick = submitExperience;
}

function renderExperiences(experiences) {
    return experiences.map(exp => `
        <div class="experience-item border-bottom pb-3 mb-3" data-id="${exp.id}">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="mb-1">${exp.position}</h6>
                    <p class="mb-1">${exp.company}</p>
                    <p class="text-muted small mb-1">
                        ${formatDate(exp.start_date)} - 
                        ${exp.is_current == 1 ? 'Hiện tại' : formatDate(exp.end_date)}
                    </p>
                    ${exp.description ? `<p class="mb-0">${exp.description}</p>` : ''}
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editExperience(${exp.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteExperience(${exp.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Add event listener for modal close
document.getElementById('experienceModal').addEventListener('hidden.bs.modal', resetExperienceForm);

// Add this function at the top of the file
document.addEventListener('DOMContentLoaded', function() {
    const isCurrentCheckbox = document.querySelector('#is_current');
    const endDateInput = document.querySelector('input[name="end_date"]');
    const startDateInput = document.querySelector('input[name="start_date"]');
    
    if (isCurrentCheckbox && endDateInput) {
        // Validate end date when it changes
        endDateInput.addEventListener('change', function() {
            if (this.value && startDateInput.value) {
                if (new Date(this.value) < new Date(startDateInput.value)) {
                    alert('Ngày kết thúc không thể trước ngày bắt đầu');
                    this.value = '';
                }
            }
        });

        // Validate end date when start date changes
        startDateInput.addEventListener('change', function() {
            if (endDateInput.value && !isCurrentCheckbox.checked) {
                if (new Date(endDateInput.value) < new Date(this.value)) {
                    alert('Ngày kết thúc không thể trước ngày bắt đầu');
                    endDateInput.value = '';
                }
            }
        });

        isCurrentCheckbox.addEventListener('change', function() {
            if (this.checked) {
                endDateInput.value = '';
                endDateInput.disabled = true;
            } else {
                endDateInput.disabled = false;
                const today = new Date().toISOString().split('T')[0];
                if (new Date(today) > new Date(startDateInput.value)) {
                    endDateInput.value = today;
                }
            }
        });
    }
});