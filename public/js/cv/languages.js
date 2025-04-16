function submitLanguage() {
    const form = document.getElementById('languageForm');
    const formData = new FormData(form);
    
    fetch(`${BASE_URL}/language/add`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('languagesList').innerHTML = renderLanguages(data.data);
            bootstrap.Modal.getInstance(document.getElementById('languageModal')).hide();
            form.reset();
            showAlert('success', 'Thêm ngoại ngữ thành công!');
        } else {
            showAlert('danger', data.error);
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi thêm ngoại ngữ!'));
}

function editLanguage(id) {
    fetch(`${BASE_URL}/language/get/${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const language = data.data;
            const form = document.getElementById('languageForm');
            
            form.querySelector('input[name="id"]').value = language.id;
            form.querySelector('input[name="name"]').value = language.name;
            form.querySelector('select[name="proficiency"]').value = language.proficiency;
            
            document.querySelector('#languageModal .modal-title').textContent = 'Chỉnh sửa ngoại ngữ';
            document.querySelector('#languageModal .btn-primary').setAttribute('onclick', 'updateLanguage()');
            
            const modal = new bootstrap.Modal(document.getElementById('languageModal'));
            modal.show();
        } else {
            showAlert('danger', data.error);
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi tải thông tin ngoại ngữ!'));
}

function updateLanguage() {
    const form = document.getElementById('languageForm');
    const formData = new FormData(form);
    const id = formData.get('id');
    
    fetch(`${BASE_URL}/language/update/${id}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('languagesList').innerHTML = renderLanguages(data.data);
            bootstrap.Modal.getInstance(document.getElementById('languageModal')).hide();
            resetLanguageForm();
            showAlert('success', 'Cập nhật ngoại ngữ thành công!');
        } else {
            showAlert('danger', data.error);
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi cập nhật ngoại ngữ!'));
}

function deleteLanguage(id) {
    if (confirm('Bạn có chắc chắn muốn xóa ngoại ngữ này?')) {
        fetch(`${BASE_URL}/language/delete/${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`#languagesList [data-id="${id}"]`).remove();
                showAlert('success', 'Xóa ngoại ngữ thành công!');
            } else {
                showAlert('danger', data.error);
            }
        })
        .catch(error => showAlert('danger', 'Có lỗi xảy ra khi xóa ngoại ngữ!'));
    }
}

function renderLanguages(languages) {
    return languages.map(lang => `
        <div class="language-item border-bottom pb-3 mb-3" data-id="${lang.id}">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">${lang.name}</h6>
                    <p class="mb-0">${lang.proficiency}</p>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-primary" 
                        onclick="editLanguage(${lang.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" 
                        onclick="deleteLanguage(${lang.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

function resetLanguageForm() {
    const form = document.getElementById('languageForm');
    form.reset();
    form.querySelector('input[name="id"]').value = '';
    document.querySelector('#languageModal .modal-title').textContent = 'Thêm ngoại ngữ';
    document.querySelector('#languageModal .btn-primary').setAttribute('onclick', 'submitLanguage()');
}

// Add event listener for modal close
document.getElementById('languageModal').addEventListener('hidden.bs.modal', resetLanguageForm);