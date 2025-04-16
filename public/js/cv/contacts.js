function submitContact() {
    const form = document.getElementById('contactForm');
    const formData = new FormData(form);
    
    fetch(`${BASE_URL}/contact/add`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('contactsList').innerHTML = renderContacts(data.data);
            bootstrap.Modal.getInstance(document.getElementById('contactModal')).hide();
            form.reset();
            showAlert('success', 'Thêm thông tin liên hệ thành công!');
        } else {
            showAlert('danger', data.error);
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi thêm thông tin liên hệ!'));
}

function editContact(id) {
    fetch(`${BASE_URL}/contact/get/${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const contact = data.data;
            const form = document.getElementById('contactForm');
            
            form.querySelector('input[name="id"]').value = contact.id;
            form.querySelector('select[name="type"]').value = contact.type;
            form.querySelector('input[name="value"]').value = contact.value;
            form.querySelector('input[name="is_primary"]').checked = contact.is_primary == 1;
            
            document.querySelector('#contactModal .modal-title').textContent = 'Chỉnh sửa thông tin liên hệ';
            document.querySelector('#contactModal .btn-primary').setAttribute('onclick', 'updateContact()');
            
            const modal = new bootstrap.Modal(document.getElementById('contactModal'));
            modal.show();
        } else {
            showAlert('danger', data.error);
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi tải thông tin liên hệ!'));
}

function updateContact() {
    const form = document.getElementById('contactForm');
    const formData = new FormData(form);
    const id = formData.get('id');
    
    fetch(`${BASE_URL}/contact/update/${id}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('contactsList').innerHTML = renderContacts(data.data);
            bootstrap.Modal.getInstance(document.getElementById('contactModal')).hide();
            resetContactForm();
            showAlert('success', 'Cập nhật thông tin liên hệ thành công!');
        } else {
            showAlert('danger', data.error);
        }
    })
    .catch(error => showAlert('danger', 'Có lỗi xảy ra khi cập nhật thông tin liên hệ!'));
}

function renderContacts(contacts) {
    return contacts.map(contact => `
        <div class="contact-item border-bottom pb-3 mb-3" data-id="${contact.id}">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-${getContactIcon(contact.type)} me-2"></i>
                        <div>
                            <h6 class="mb-1">${contact.type.charAt(0).toUpperCase() + contact.type.slice(1)}</h6>
                            <p class="mb-0">
                                ${contact.type === 'email' ? `
                                    <a href="mailto:${contact.value}">${contact.value}</a>
                                ` : contact.type === 'phone' ? `
                                    <a href="tel:${contact.value}">${contact.value}</a>
                                ` : `
                                    <a href="${contact.value}" target="_blank">${contact.value}</a>
                                `}
                                ${contact.is_primary == 1 ? '<span class="badge bg-primary ms-2">Chính</span>' : ''}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-primary"
                        onclick="editContact(${contact.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger"
                        onclick="deleteContact(${contact.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

function deleteContact(id) {
    if (confirm('Bạn có chắc chắn muốn xóa thông tin liên hệ này?')) {
        fetch(`${BASE_URL}/contact/delete/${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`#contactsList [data-id="${id}"]`).remove();
                showAlert('success', 'Xóa thông tin liên hệ thành công!');
            } else {
                showAlert('danger', data.error);
            }
        })
        .catch(error => showAlert('danger', 'Có lỗi xảy ra khi xóa thông tin liên hệ!'));
    }
}

function getContactIcon(type) {
    const icons = {
        'website': 'globe',
        'github': 'github',
        'linkedin': 'linkedin',
        'facebook': 'facebook',
        'twitter': 'twitter',
        'email': 'envelope',
        'phone': 'phone'
    };
    return icons[type] || 'link';
}

// Reset form when modal is closed
document.addEventListener('DOMContentLoaded', function() {
    const contactModal = document.getElementById('contactModal');
    if (contactModal) {
        contactModal.addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('contactForm');
            form.reset();
            form.querySelector('input[name="id"]').value = '';
            document.querySelector('#contactModal .modal-title').textContent = 'Thêm thông tin liên hệ';
            document.querySelector('#contactModal .btn-primary').setAttribute('onclick', 'submitContact()');
        });
    }
});

// Add this at the end of the file
document.addEventListener('DOMContentLoaded', function() {
    // Initialize icons for existing contact items
    document.querySelectorAll('[data-contact-type]').forEach(icon => {
        const type = icon.getAttribute('data-contact-type');
        icon.classList.add(`fa-${getContactIcon(type)}`);
    });
});