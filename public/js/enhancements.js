// JavaScript Enhancements for ServiceConnect

document.addEventListener('DOMContentLoaded', function() {
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Auto-hide notifications after 5 seconds
    const notifications = document.querySelectorAll('.alert:not(.alert-permanent)');
    notifications.forEach(notification => {
        setTimeout(() => {
            notification.style.transition = 'opacity 0.5s';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 500);
        }, 5000);
    });

    // Enhanced form validation
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                // Show validation message
                showNotification('Veuillez remplir tous les champs obligatoires', 'danger');
            }
        });
    });

    // Loading states for buttons
    const buttons = document.querySelectorAll('[data-loading]');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Chargement...';
            
            // Reset after 3 seconds (for demo)
            setTimeout(() => {
                this.disabled = false;
                this.innerHTML = originalText;
            }, 3000);
        });
    });

    // Image preview for file uploads
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const files = e.target.files;
            const previewContainer = document.getElementById('imagePreview');
            
            if (previewContainer) {
                previewContainer.innerHTML = '';
                
                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'col-md-3 mb-3';
                            div.innerHTML = `
                                <div class="position-relative">
                                    <img src="${e.target.result}" class="img-fluid rounded" alt="Preview">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="this.parentElement.parentElement.remove()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            `;
                            previewContainer.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    });

    // Search functionality
    const searchInputs = document.querySelectorAll('[data-search]');
    searchInputs.forEach(input => {
        let searchTimeout;
        
        input.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value;
            const target = this.dataset.search;
            
            searchTimeout = setTimeout(() => {
                performSearch(query, target);
            }, 300);
        });
    });

    // Dynamic content loading
    const loadMoreButtons = document.querySelectorAll('[data-load-more]');
    loadMoreButtons.forEach(button => {
        button.addEventListener('click', function() {
            const url = this.dataset.loadMore;
            const target = this.dataset.target;
            
            loadMoreContent(url, target, this);
        });
    });

    // Tooltips
    const tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipElements.forEach(element => {
        new bootstrap.Tooltip(element);
    });

    // Copy to clipboard functionality
    const copyButtons = document.querySelectorAll('[data-copy]');
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const text = this.dataset.copy;
            
            navigator.clipboard.writeText(text).then(() => {
                showNotification('Copié dans le presse-papiers!', 'success');
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    this.innerHTML = originalText;
                }, 2000);
            });
        });
    });

    // Back to top button
    const backToTopButton = document.createElement('button');
    backToTopButton.className = 'btn btn-primary position-fixed bottom-0 end-0 m-3';
    backToTopButton.style.cssText = 'z-index: 1000; border-radius: 50%; width: 50px; height: 50px; display: none;';
    backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopButton.setAttribute('aria-label', 'Retour en haut');
    
    document.body.appendChild(backToTopButton);
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.style.display = 'block';
        } else {
            backToTopButton.style.display = 'none';
        }
    });
    
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});

// Utility functions
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
    notification.style.cssText = 'z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

function performSearch(query, target) {
    // Implement search logic here
    console.log(`Searching for "${query}" in ${target}`);
    // This would typically make an AJAX call to your search endpoint
}

function loadMoreContent(url, target, button) {
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Chargement...';
    
    // Simulate AJAX call
    setTimeout(() => {
        // Add your content loading logic here
        button.disabled = false;
        button.innerHTML = 'Charger plus';
    }, 1000);
}

// Export functions for global use
window.showNotification = showNotification;
window.performSearch = performSearch;
window.loadMoreContent = loadMoreContent;
