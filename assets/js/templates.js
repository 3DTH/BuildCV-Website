document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterButtons = document.querySelectorAll('.btn-filter');
    const templateCards = document.querySelectorAll('.template-card');
    const searchInput = document.querySelector('.search-box input');

    // Only add event listeners if elements exist (we're on the templates page)
    if (searchInput && filterButtons.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                const filter = button.dataset.filter;
                
                templateCards.forEach(card => {
                    if (filter === 'all' || card.classList.contains(filter)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Search functionality
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
        
            templateCards.forEach(card => {
                const templateName = card.querySelector('.template-name').textContent.toLowerCase();
                const templateDesc = card.querySelector('.template-description').textContent.toLowerCase();
                
                if (templateName.includes(searchTerm) || templateDesc.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
    // Template preview functionality
    const previewButtons = document.querySelectorAll('.btn-preview');
    const templatePreviewModal = document.getElementById('templatePreviewModal');
    
    if (previewButtons.length > 0 && templatePreviewModal) {
        const previewModal = new bootstrap.Modal(templatePreviewModal);
        const previewContent = document.querySelector('.template-preview-content');

        previewButtons.forEach(button => {
            button.addEventListener('click', async () => {
                const templateId = button.dataset.templateId;
                
                // Show loading state
                previewContent.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>';
                previewModal.show();
                
                try {
                    const response = await fetch(`${BASE_URL}/templates/preview/${templateId}`);
                    const data = await response.json();
                    
                    if (data.success) {
                        previewContent.innerHTML = data.html;
                    } else {
                        previewContent.innerHTML = `<div class="alert alert-danger">${data.message || 'Không thể tải mẫu xem trước'}</div>`;
                    }
                } catch (error) {
                    console.error('Error loading template preview:', error);
                    previewContent.innerHTML = '<div class="alert alert-danger">Có lỗi xảy ra khi tải mẫu xem trước</div>';
                }
            });
        });
    }

    const templateItems = document.querySelectorAll('.template-item');
    const templateIdInput = document.getElementById('template_id');
    const cvForm = document.getElementById('cvForm');

    templateItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            templateItems.forEach(i => i.classList.remove('active'));
            
            // Add active class to clicked item
            this.classList.add('active');
            
            const templateId = this.dataset.templateId;
            const templateImg = this.querySelector('img').src;
            const templateName = this.querySelector('.template-name').textContent;
            
            // Update hidden input value
            templateIdInput.value = templateId;
            
            // Update URL without refreshing the page
            const url = new URL(window.location.href);
            url.searchParams.set('template', templateId);
            window.history.pushState({}, '', url);
            
            // Update preview section immediately
            const cardBody = document.querySelector('.card.shadow.mb-4 .card-body');
            if (cardBody) {
                cardBody.innerHTML = `
                    <div class="selected-template">
                        <img src="${templateImg}" class="img-fluid rounded mb-3" alt="Template Preview">
                        <h5 class="template-name">${templateName}</h5>
                    </div>
                `;
            }
        });
    });

    // Form validation
    cvForm.addEventListener('submit', function(e) {
        if (!templateIdInput.value) {
            e.preventDefault();
            alert('Vui lòng chọn mẫu CV trước khi tiếp tục');
        }
    });
    
});
