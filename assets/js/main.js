// Form Validation
document.addEventListener('DOMContentLoaded', function() {
    // Bootstrap form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Password confirmation validation
    const passwordConfirmFields = document.querySelectorAll('input[name="password_confirm"]');
    passwordConfirmFields.forEach(field => {
        field.addEventListener('input', function() {
            const password = this.form.querySelector('input[name="password"]');
            if (this.value !== password.value) {
                this.setCustomValidity('Mật khẩu xác nhận không khớp');
            } else {
                this.setCustomValidity('');
            }
        });
    });
});

// CV Section Management
class CVManager {
    constructor() {
        this.bindEvents();
    }

    bindEvents() {
        // Add Education
        document.querySelectorAll('.add-education').forEach(btn => {
            btn.addEventListener('click', () => this.addEducation());
        });

        // Add Experience
        document.querySelectorAll('.add-experience').forEach(btn => {
            btn.addEventListener('click', () => this.addExperience());
        });

        // Add Skill
        document.querySelectorAll('.add-skill').forEach(btn => {
            btn.addEventListener('click', () => this.addSkill());
        });

        // Delete buttons
        document.addEventListener('click', e => {
            if (e.target.matches('.delete-section')) {
                this.deleteSection(e.target);
            }
        });
    }

    async addEducation() {
        const cvId = document.querySelector('#cv-id').value;
        const data = {
            institution: document.querySelector('#education-institution').value,
            degree: document.querySelector('#education-degree').value,
            field_of_study: document.querySelector('#education-field').value,
            start_date: document.querySelector('#education-start').value,
            end_date: document.querySelector('#education-end').value,
            description: document.querySelector('#education-description').value
        };

        try {
            showSpinner();
            const response = await this.makeRequest('/cv/addEducation', {
                method: 'POST',
                body: JSON.stringify({ cv_id: cvId, ...data })
            });

            if (response.success) {
                showAlert('success', 'Thêm học vấn thành công!');
                location.reload();
            } else {
                showAlert('danger', 'Có lỗi xảy ra!');
            }
        } catch (error) {
            showAlert('danger', 'Có lỗi xảy ra!');
        } finally {
            hideSpinner();
        }
    }

    async addExperience() {
        // Tương tự như addEducation
    }

    async addSkill() {
        // Tương tự như addEducation
    }

    async deleteSection(button) {
        if (confirm('Bạn có chắc chắn muốn xóa mục này?')) {
            const sectionId = button.dataset.id;
            const type = button.dataset.type;

            try {
                showSpinner();
                const response = await this.makeRequest(`/cv/delete${type}/${sectionId}`, {
                    method: 'POST'
                });

                if (response.success) {
                    button.closest('.cv-section').remove();
                    showAlert('success', 'Xóa thành công!');
                } else {
                    showAlert('danger', 'Có lỗi xảy ra!');
                }
            } catch (error) {
                showAlert('danger', 'Có lỗi xảy ra!');
            } finally {
                hideSpinner();
            }
        }
    }

    async makeRequest(url, options = {}) {
        options.headers = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        const response = await fetch(url, options);
        return response.json();
    }
}

// Utility Functions
function showSpinner() {
    const spinner = document.createElement('div');
    spinner.className = 'spinner-overlay';
    spinner.innerHTML = '<div class="spinner"></div>';
    document.body.appendChild(spinner);
}

function hideSpinner() {
    const spinner = document.querySelector('.spinner-overlay');
    if (spinner) {
        spinner.remove();
    }
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);

    // Auto hide after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    new CVManager();
});

// Image Preview
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.querySelector('#image-preview');
            if (preview) {
                preview.src = e.target.result;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Sortable Sections
if (typeof Sortable !== 'undefined') {
    document.querySelectorAll('.sortable-sections').forEach(el => {
        new Sortable(el, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function(evt) {
                // Update order in database
                const sections = evt.to.children;
                const orders = Array.from(sections).map((section, index) => ({
                    id: section.dataset.id,
                    order: index
                }));

                fetch('/cv/updateOrder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(orders)
                });
            }
        });
    });
}