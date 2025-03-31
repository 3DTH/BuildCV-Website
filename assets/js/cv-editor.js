document.addEventListener('DOMContentLoaded', function() {
    // Initialize all tooltips
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));

    // Form submission handlers
    setupFormHandlers();
    
    // Section-specific handlers
    setupEducationHandlers();
    setupExperienceHandlers();
    setupSkillsHandlers();
    setupLanguagesHandlers();
    setupCertificatesHandlers();
    setupProjectsHandlers();
    setupContactsHandlers();
});

// Form submission handlers
function setupFormHandlers() {
    const forms = document.querySelectorAll('form[data-ajax="true"]');
    forms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const endpoint = form.getAttribute('action');

            try {
                showLoading(form);
                const response = await fetch(endpoint, {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    showSuccess('Changes saved successfully');
                    updateSection(data.section, data.html);
                } else {
                    showError(data.error || 'An error occurred');
                }
            } catch (error) {
                showError('Failed to save changes');
                console.error(error);
            } finally {
                hideLoading(form);
            }
        });
    });
}

// Section-specific handlers
function setupEducationHandlers() {
    const addEducationBtn = document.getElementById('addEducation');
    if (addEducationBtn) {
        addEducationBtn.addEventListener('click', () => {
            showModal('educationModal', {
                title: 'Add Education',
                action: `${BASE_URL}/cv/addEducation`
            });
        });
    }
}

function setupExperienceHandlers() {
    const addExperienceBtn = document.getElementById('addExperience');
    if (addExperienceBtn) {
        addExperienceBtn.addEventListener('click', () => {
            showModal('experienceModal', {
                title: 'Add Experience',
                action: `${BASE_URL}/cv/addExperience`
            });
        });
    }
}

function setupSkillsHandlers() {
    const addSkillBtn = document.getElementById('addSkill');
    if (addSkillBtn) {
        addSkillBtn.addEventListener('click', () => {
            showModal('skillModal', {
                title: 'Add Skill',
                action: `${BASE_URL}/cv/addSkill`
            });
        });
    }
}

// Utility functions
function showLoading(element) {
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay';
    overlay.innerHTML = `
        <div class="spinner-border text-primary spinner" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    `;
    element.appendChild(overlay);
}

function hideLoading(element) {
    const overlay = element.querySelector('.loading-overlay');
    if (overlay) {
        overlay.remove();
    }
}

function showSuccess(message) {
    const toast = new bootstrap.Toast(createToast('success', message));
    toast.show();
}

function showError(message) {
    const toast = new bootstrap.Toast(createToast('danger', message));
    toast.show();
}

function createToast(type, message) {
    const toastContainer = document.getElementById('toastContainer') || createToastContainer();
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    return toast;
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
    document.body.appendChild(container);
    return container;
}

function showModal(modalId, options = {}) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    if (options.title) {
        modal.querySelector('.modal-title').textContent = options.title;
    }
    if (options.action) {
        modal.querySelector('form').setAttribute('action', options.action);
    }

    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

function updateSection(sectionId, html) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.innerHTML = html;
    }
}

// Drag and drop functionality
function initializeSortable(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    new Sortable(container, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'item-dragging',
        onEnd: async function(evt) {
            const items = [...evt.to.children].map((item, index) => ({
                id: item.dataset.id,
                order: index
            }));

            try {
                const response = await fetch(`${BASE_URL}/cv/updateOrder`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        section: containerId,
                        items: items
                    })
                });
                const data = await response.json();
                if (!data.success) {
                    showError('Failed to update order');
                }
            } catch (error) {
                console.error('Error updating order:', error);
                showError('Failed to update order');
            }
        }
    });
}