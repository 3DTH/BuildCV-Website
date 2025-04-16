function submitSkill() {
    const form = document.getElementById('skillForm');
    const formData = new FormData(form);
    
    fetch(`${BASE_URL}/skill/add`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('skillsList').innerHTML = renderSkills(data.data);
            bootstrap.Modal.getInstance(document.getElementById('skillModal')).hide();
            form.reset();
            showAlert('success', 'Thêm kỹ năng thành công!');
        } else {
            showAlert('danger', data.error);
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi thêm kỹ năng!'));
}

function editSkill(id) {
    fetch(`${BASE_URL}/skill/get/${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const skill = data.data;
            const form = document.getElementById('skillForm');
            
            form.querySelector('input[name="id"]').value = skill.id;
            form.querySelector('input[name="name"]').value = skill.name;
            form.querySelector('input[name="level"]').value = skill.level;
            
            document.querySelector('#skillModal .modal-title').textContent = 'Chỉnh sửa kỹ năng';
            document.querySelector('#skillModal .btn-primary').setAttribute('onclick', 'updateSkill()');
            
            const modal = new bootstrap.Modal(document.getElementById('skillModal'));
            modal.show();
        } else {
            showAlert('danger', data.error);
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi tải thông tin kỹ năng!'));
}

function updateSkill() {
    const form = document.getElementById('skillForm');
    const formData = new FormData(form);
    const id = formData.get('id');
    
    fetch(`${BASE_URL}/skill/update/${id}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('skillsList').innerHTML = renderSkills(data.data);
            bootstrap.Modal.getInstance(document.getElementById('skillModal')).hide();
            resetSkillForm();
            showAlert('success', 'Cập nhật kỹ năng thành công!');
        } else {
            showAlert('danger', data.error);
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi cập nhật kỹ năng!'));
}

function deleteSkill(id) {
    if (confirm('Bạn có chắc chắn muốn xóa kỹ năng này?')) {
        fetch(`${BASE_URL}/skill/delete/${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`#skillsList [data-id="${id}"]`).remove();
                showAlert('success', 'Xóa kỹ năng thành công!');
            } else {
                showAlert('danger', data.error);
            }
        })
        .catch(error => showAlert('danger', 'Có lỗi xảy ra khi xóa kỹ năng!'));
    }
}

// Add this at the end of the file
document.addEventListener('DOMContentLoaded', function() {
    const levelInput = document.querySelector('#skillForm input[name="level"]');
    const levelValue = document.getElementById('levelValue');
    
    if (levelInput && levelValue) {
        levelInput.addEventListener('input', function() {
            levelValue.textContent = this.value + '%';
        });
    }
});

function renderSkills(skills) {
    return skills.map(skill => `
        <div class="skill-item border-bottom pb-3 mb-3" data-id="${skill.id}">
            <div class="d-flex justify-content-between align-items-center">
                <div class="flex-grow-1 me-3">
                    <h6 class="mb-1">${skill.name}</h6>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" 
                            style="width: ${skill.level}%"
                            aria-valuenow="${skill.level}" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                        </div>
                    </div>
                    <small class="text-muted">${skill.level}%</small>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-primary"
                        onclick="editSkill(${skill.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger"
                        onclick="deleteSkill(${skill.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

function resetSkillForm() {
    const form = document.getElementById('skillForm');
    form.reset();
    form.querySelector('input[name="id"]').value = '';
    document.querySelector('#skillModal .modal-title').textContent = 'Thêm kỹ năng';
    document.querySelector('#skillModal .btn-primary').setAttribute('onclick', 'submitSkill()');
}

// Add event listener for modal close
document.getElementById('skillModal').addEventListener('hidden.bs.modal', resetSkillForm);