function updatePreview() {
    const previewContainer = document.getElementById('cvPreview');
    if (!previewContainer) return;

    const templateId = previewContainer.dataset.template;
    if (!templateId) return;

    // Show loading state
    previewContainer.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary"></div></div>';
    
    // Collect all CV data
    const cvData = {
        personal: {
            name: document.querySelector('[name="full_name"]')?.value,
            title: document.querySelector('[name="title"]')?.value,
            email: document.querySelector('[name="email"]')?.value,
            phone: document.querySelector('[name="phone"]')?.value,
            address: document.querySelector('[name="address"]')?.value,
            summary: document.querySelector('[name="summary"]')?.value
        },
        contacts: Array.from(document.querySelectorAll('.contact-item')).map(item => ({
            type: item.querySelector('[data-contact-type]')?.dataset.contactType,
            value: item.querySelector('a')?.textContent || item.querySelector('p')?.textContent,
            isPrimary: item.querySelector('.badge') !== null
        })),
        experiences: Array.from(document.querySelectorAll('.experience-item')).map(item => ({
            position: item.querySelector('h6')?.textContent,
            company: item.querySelector('p:first-of-type')?.textContent,
            start_date: item.dataset.startDate,
            end_date: item.dataset.endDate,
            is_current: item.dataset.isCurrent === 'true',
            description: item.querySelector('p:last-of-type')?.textContent
        })),
        education: Array.from(document.querySelectorAll('.education-item')).map(item => ({
            institution: item.querySelector('h6')?.textContent,
            degree: item.querySelector('p:first-of-type')?.textContent.split(' - ')[0],
            fieldOfStudy: item.querySelector('p:first-of-type')?.textContent.split(' - ')[1],
            period: item.querySelector('.text-muted')?.textContent,
            description: item.querySelector('p:last-of-type')?.textContent
        })),
        skills: Array.from(document.querySelectorAll('.skill-item')).map(item => ({
            name: item.querySelector('h6')?.textContent,
            level: item.querySelector('.progress-bar')?.style.width || '0%'
        })),
        languages: Array.from(document.querySelectorAll('.language-item')).map(item => ({
            name: item.querySelector('h6')?.textContent,
            proficiency: item.querySelector('p')?.textContent
        })),
        certificates: Array.from(document.querySelectorAll('.certificate-item')).map(item => ({
            name: item.querySelector('h6')?.textContent,
            issuer: item.querySelector('p:first-of-type')?.textContent,
            date: item.querySelector('.text-muted')?.textContent,
            description: item.querySelector('p:last-of-type')?.textContent
        })),
        projects: Array.from(document.querySelectorAll('.project-item')).map(item => ({
            title: item.querySelector('h6')?.textContent,
            role: item.querySelector('p:first-of-type')?.textContent,
            period: item.querySelector('.text-muted')?.textContent,
            url: item.querySelector('a')?.href,
            description: item.querySelector('p:last-of-type')?.textContent
        }))
    };

    // Update preview with template
    fetch(`${BASE_URL}/cv/renderPreview/${templateId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(cvData)
    })
    .then(response => response.text())
    .then(html => {
        previewContainer.innerHTML = html;
    });
}

// Add these event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Chỉ update preview khi mở modal
    const previewModal = document.getElementById('previewModal');
    if (previewModal) {
        previewModal.addEventListener('show.bs.modal', updatePreview);
    }
    
    // Listen for changes in forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', () => {
            setTimeout(() => {
                // Update preview if modal is open
                if (previewModal.classList.contains('show')) {
                    updatePreview();
                }
            }, 500);
        });
    });
    
    // Listen for modal closes (new items added)
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (modal.id !== 'previewModal') {
            modal.addEventListener('hidden.bs.modal', () => {
                setTimeout(() => {
                    // Update preview if preview modal is open
                    if (previewModal.classList.contains('show')) {
                        updatePreview();
                    }
                }, 500);
            });
        }
    });
});

function printCV() {
    const printWindow = window.open('', '_blank');
    const content = document.getElementById('cvPreview').innerHTML;
    const styles = Array.from(document.styleSheets)
        .map(sheet => {
            try {
                return Array.from(sheet.cssRules)
                    .map(rule => rule.cssText)
                    .join('\n');
            } catch (e) {
                return '';
            }
        })
        .join('\n');

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Print CV</title>
            <style>${styles}</style>
        </head>
        <body>
            <div class="cv-preview-container">${content}</div>
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

function exportPDF() {
    const content = document.getElementById('cvPreview').innerHTML;
    const styles = Array.from(document.styleSheets)
        .map(sheet => {
            try {
                return Array.from(sheet.cssRules)
                    .map(rule => rule.cssText)
                    .join('\n');
            } catch (e) {
                return '';
            }
        })
        .join('\n');

    // Show loading state
    const previewContainer = document.getElementById('cvPreview');
    previewContainer.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary"></div><p>Đang tạo PDF...</p></div>';

    fetch(`${BASE_URL}/cv/generatePDF`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            content: content,
            styles: styles
        })
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'cv.pdf';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        
        // Restore preview
        updatePreview();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi tạo PDF. Vui lòng thử lại sau.');
        updatePreview();
    });
}