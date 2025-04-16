function submitProject() {
    const form = document.getElementById('projectForm');
    const formData = new FormData(form);
    
    fetch(`${BASE_URL}/project/add`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('projectsList').innerHTML = renderProjects(data.data);
            bootstrap.Modal.getInstance(document.getElementById('projectModal')).hide();
            form.reset();
            showAlert('success', 'Thêm dự án thành công!');
        } else {
            showAlert('danger', data.error);
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi thêm dự án!'));
}

function editProject(id) {
    fetch(`${BASE_URL}/project/get/${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const project = data.data;
            const form = document.getElementById('projectForm');
            
            form.querySelector('input[name="id"]').value = project.id;
            form.querySelector('input[name="title"]').value = project.title;
            form.querySelector('input[name="role"]').value = project.role;
            form.querySelector('input[name="start_date"]').value = project.start_date;
            form.querySelector('input[name="end_date"]').value = project.end_date || '';
            form.querySelector('input[name="url"]').value = project.url || '';
            form.querySelector('textarea[name="description"]').value = project.description;
            
            document.querySelector('#projectModal .modal-title').textContent = 'Chỉnh sửa dự án';
            document.querySelector('#projectModal .btn-primary').setAttribute('onclick', 'updateProject()');
            
            const modal = new bootstrap.Modal(document.getElementById('projectModal'));
            modal.show();
        } else {
            showAlert('danger', data.error);
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi tải thông tin dự án!'));
}

function updateProject() {
    const form = document.getElementById('projectForm');
    const formData = new FormData(form);
    const id = formData.get('id');
    
    fetch(`${BASE_URL}/project/update/${id}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('projectsList').innerHTML = renderProjects(data.data);
            bootstrap.Modal.getInstance(document.getElementById('projectModal')).hide();
            resetProjectForm();
            showAlert('success', 'Cập nhật dự án thành công!');
        } else {
            showAlert('danger', data.error);
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi cập nhật dự án!'));
}

function deleteProject(id) {
    if (confirm('Bạn có chắc chắn muốn xóa dự án này?')) {
        fetch(`${BASE_URL}/project/delete/${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`#projectsList [data-id="${id}"]`).remove();
                showAlert('success', 'Xóa dự án thành công!');
            } else {
                showAlert('danger', data.error);
            }
        })
        .catch(error => showAlert('danger', 'Có lỗi xảy ra khi xóa dự án!'));
    }
}

function renderProjects(projects) {
    return projects.map(project => `
        <div class="project-item border-bottom pb-3 mb-3" data-id="${project.id}">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="mb-1">${project.title}</h6>
                    <p class="mb-1">${project.role}</p>
                    <p class="text-muted small mb-1">
                        ${new Date(project.start_date).toLocaleDateString('vi-VN', { month: '2-digit', year: 'numeric' })} -
                        ${project.end_date ? new Date(project.end_date).toLocaleDateString('vi-VN', { month: '2-digit', year: 'numeric' }) : 'Hiện tại'}
                    </p>
                    ${project.url ? `
                        <p class="mb-1">
                            <a href="${project.url}" target="_blank" class="text-primary">
                                <i class="fas fa-external-link-alt"></i> Xem dự án
                            </a>
                        </p>
                    ` : ''}
                    ${project.description ? `<p class="mb-0">${project.description.replace(/\n/g, '<br>')}</p>` : ''}
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-primary"
                        onclick="editProject(${project.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger"
                        onclick="deleteProject(${project.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Add date validation
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.querySelector('#projectForm input[name="start_date"]');
    const endDateInput = document.querySelector('#projectForm input[name="end_date"]');
    
    if (startDateInput && endDateInput) {
        // Set max date for start date to today
        const today = new Date().toISOString().split('T')[0];
        startDateInput.max = today;
        
        // Update min date for end date when start date changes
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
        });
    }
});

function resetProjectForm() {
    const form = document.getElementById('projectForm');
    form.reset();
    form.querySelector('input[name="id"]').value = '';
    document.querySelector('#projectModal .modal-title').textContent = 'Thêm dự án';
    document.querySelector('#projectModal .btn-primary').setAttribute('onclick', 'submitProject()');
}

// Add event listener for modal close
document.getElementById('projectModal').addEventListener('hidden.bs.modal', resetProjectForm);