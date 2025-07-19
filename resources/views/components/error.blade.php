<!-- Error Component -->
<div id="error-modal" class="error-modal" style="display: none;">
    <div class="error-modal-backdrop" onclick="ErrorComponent.hide()"></div>
    <div class="error-modal-content">
        <div class="error-header">
            <div class="error-icon">
                <i id="error-icon" class="fas fa-exclamation-triangle"></i>
            </div>
            <button class="error-close-btn" onclick="ErrorComponent.hide()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="error-body">
            <h4 id="error-title">Hata Oluştu</h4>
            <p id="error-message">Beklenmeyen bir hata oluştu. Lütfen tekrar deneyin.</p>
            <div id="error-details" class="error-details" style="display: none;">
                <button class="btn btn-link p-0 mb-2" onclick="ErrorComponent.toggleDetails()">
                    <i class="fas fa-chevron-down me-1" id="details-toggle"></i>Detayları Göster
                </button>
                <div id="error-details-content" class="error-details-content" style="display: none;">
                    <pre id="error-stack"></pre>
                </div>
            </div>
        </div>
        
        <div class="error-actions">
            <button id="error-retry-btn" class="btn btn-primary me-2" onclick="ErrorComponent.retry()" style="display: none;">
                <i class="fas fa-redo me-1"></i>Tekrar Dene
            </button>
            <button class="btn btn-secondary me-2" onclick="ErrorComponent.hide()">
                <i class="fas fa-times me-1"></i>Kapat
            </button>
            <button class="btn btn-outline-warning" onclick="ErrorComponent.report()">
                <i class="fas fa-bug me-1"></i>Rapor Et
            </button>
        </div>
    </div>
</div>

<!-- Toast Error Notifications -->
<div id="error-toast-container" class="error-toast-container">
    <!-- Toast messages will be added here dynamically -->
</div>

<!-- Inline Error Display -->
<div class="inline-error" style="display: none;" id="inline-error-template">
    <div class="alert alert-danger" role="alert">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle fa-lg"></i>
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="alert-heading error-title">Hata</h6>
                <p class="mb-2 error-message">Bir hata oluştu</p>
                <div class="error-actions">
                    <button class="btn btn-sm btn-outline-danger me-2 retry-btn" style="display: none;">
                        <i class="fas fa-redo me-1"></i>Tekrar Dene
                    </button>
                    <button class="btn btn-sm btn-outline-secondary dismiss-btn">
                        <i class="fas fa-times me-1"></i>Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Network Error Banner -->
<div id="network-error-banner" class="network-error-banner" style="display: none;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fas fa-wifi text-warning me-2"></i>
                <span><strong>Bağlantı Sorunu:</strong> İnternet bağlantınızı kontrol edin</span>
            </div>
            <button class="btn btn-sm btn-link text-white" onclick="ErrorComponent.hideNetworkBanner()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

<style>
/* Error Modal Styles */
.error-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.error-modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(3px);
}

.error-modal-content {
    position: relative;
    background: white;
    border-radius: 15px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    max-width: 500px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
    animation: errorSlideIn 0.3s ease-out;
}

@keyframes errorSlideIn {
    from {
        opacity: 0;
        transform: translateY(-30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.error-header {
    position: relative;
    padding: 1.5rem;
    text-align: center;
    border-bottom: 1px solid #eee;
}

.error-icon {
    font-size: 3rem;
    margin-bottom: 0.5rem;
}

.error-icon .fa-exclamation-triangle {
    color: #dc3545;
}

.error-icon .fa-wifi {
    color: #ffc107;
}

.error-icon .fa-server {
    color: #6c757d;
}

.error-close-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 1.2rem;
    color: #6c757d;
    cursor: pointer;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.error-close-btn:hover {
    background: #f8f9fa;
    color: #495057;
}

.error-body {
    padding: 1.5rem;
}

.error-body h4 {
    color: #dc3545;
    margin-bottom: 1rem;
}

.error-details-content {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid #dc3545;
    margin-top: 1rem;
}

.error-details-content pre {
    color: #6c757d;
    font-size: 0.85rem;
    max-height: 200px;
    overflow-y: auto;
    margin: 0;
}

.error-actions {
    padding: 1rem 1.5rem 1.5rem;
    border-top: 1px solid #eee;
    text-align: right;
}

/* Toast Error Styles */
.error-toast-container {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 9999;
    max-width: 350px;
}

.error-toast {
    background: white;
    border: 1px solid #dc3545;
    border-left: 4px solid #dc3545;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.15);
    animation: toastSlideIn 0.3s ease-out;
    position: relative;
}

@keyframes toastSlideIn {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.error-toast.warning {
    border-color: #ffc107;
    border-left-color: #ffc107;
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.15);
}

.error-toast.info {
    border-color: #0dcaf0;
    border-left-color: #0dcaf0;
    box-shadow: 0 4px 12px rgba(13, 202, 240, 0.15);
}

.error-toast-close {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
}

.error-toast-icon {
    margin-right: 0.5rem;
}

/* Network Error Banner */
.network-error-banner {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: #dc3545;
    color: white;
    padding: 0.75rem 0;
    z-index: 9998;
    animation: bannerSlideDown 0.3s ease-out;
}

@keyframes bannerSlideDown {
    from {
        opacity: 0;
        transform: translateY(-100%);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Inline Error Styles */
.inline-error {
    margin: 1rem 0;
}

.inline-error .alert {
    border: none;
    border-left: 4px solid #dc3545;
    background: #f8d7da;
    color: #721c24;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .error-modal-content {
        margin: 1rem;
        width: calc(100% - 2rem);
    }
    
    .error-toast-container {
        right: 10px;
        left: 10px;
        max-width: none;
    }
    
    .error-actions {
        text-align: center;
    }
    
    .error-actions .btn {
        width: 100%;
        margin-bottom: 0.5rem;
        margin-right: 0 !important;
    }
}
</style>

<script>
// Error Component JavaScript
window.ErrorComponent = {
    currentRetryFunction: null,
    
    // Show modal error
    show: function(title, message, options = {}) {
        const modal = document.getElementById('error-modal');
        const icon = document.getElementById('error-icon');
        
        document.getElementById('error-title').textContent = title;
        document.getElementById('error-message').textContent = message;
        
        // Set appropriate icon
        if (options.type === 'network') {
            icon.className = 'fas fa-wifi';
        } else if (options.type === 'server') {
            icon.className = 'fas fa-server';
        } else {
            icon.className = 'fas fa-exclamation-triangle';
        }
        
        // Handle retry function
        const retryBtn = document.getElementById('error-retry-btn');
        if (options.retryFunction) {
            this.currentRetryFunction = options.retryFunction;
            retryBtn.style.display = 'inline-block';
        } else {
            retryBtn.style.display = 'none';
        }
        
        // Handle error details
        if (options.details) {
            document.getElementById('error-stack').textContent = options.details;
            document.getElementById('error-details').style.display = 'block';
        } else {
            document.getElementById('error-details').style.display = 'none';
        }
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    },
    
    // Hide modal error
    hide: function() {
        document.getElementById('error-modal').style.display = 'none';
        document.body.style.overflow = '';
        this.currentRetryFunction = null;
    },
    
    // Retry function
    retry: function() {
        if (this.currentRetryFunction) {
            this.hide();
            this.currentRetryFunction();
        }
    },
    
    // Toggle error details
    toggleDetails: function() {
        const content = document.getElementById('error-details-content');
        const toggle = document.getElementById('details-toggle');
        
        if (content.style.display === 'none') {
            content.style.display = 'block';
            toggle.className = 'fas fa-chevron-up me-1';
            toggle.nextSibling.textContent = 'Detayları Gizle';
        } else {
            content.style.display = 'none';
            toggle.className = 'fas fa-chevron-down me-1';
            toggle.nextSibling.textContent = 'Detayları Göster';
        }
    },
    
    // Report error
    report: function() {
        const title = document.getElementById('error-title').textContent;
        const message = document.getElementById('error-message').textContent;
        const details = document.getElementById('error-stack').textContent;
        
        const errorReport = {
            title: title,
            message: message,
            details: details,
            url: window.location.href,
            userAgent: navigator.userAgent,
            timestamp: new Date().toISOString()
        };
        
        console.log('Error reported:', errorReport);
        
        // Gerçek projede bu sunucuya gönderilebilir
        this.showToast('Hata raporu alındı, teşekkürler!', 'success');
        this.hide();
    },
    
    // Show toast notification
    showToast: function(message, type = 'error', duration = 5000) {
        const container = document.getElementById('error-toast-container');
        const toast = document.createElement('div');
        toast.className = `error-toast ${type}`;
        
        const iconClass = type === 'error' ? 'fa-exclamation-circle' : 
                         type === 'warning' ? 'fa-exclamation-triangle' :
                         type === 'success' ? 'fa-check-circle' : 'fa-info-circle';
        
        toast.innerHTML = `
            <button class="error-toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
            <div class="d-flex align-items-start">
                <i class="fas ${iconClass} error-toast-icon"></i>
                <div>
                    <div class="fw-bold">${type.charAt(0).toUpperCase() + type.slice(1)}</div>
                    <div>${message}</div>
                </div>
            </div>
        `;
        
        container.appendChild(toast);
        
        // Auto-remove after duration
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, duration);
        
        return toast;
    },
    
    // Show inline error
    showInline: function(containerId, title, message, retryFunction = null) {
        const container = document.getElementById(containerId);
        const template = document.getElementById('inline-error-template');
        const errorElement = template.cloneNode(true);
        
        errorElement.id = 'inline-error-' + Date.now();
        errorElement.style.display = 'block';
        
        errorElement.querySelector('.error-title').textContent = title;
        errorElement.querySelector('.error-message').textContent = message;
        
        const retryBtn = errorElement.querySelector('.retry-btn');
        if (retryFunction) {
            retryBtn.style.display = 'inline-block';
            retryBtn.onclick = function() {
                errorElement.remove();
                retryFunction();
            };
        }
        
        errorElement.querySelector('.dismiss-btn').onclick = function() {
            errorElement.remove();
        };
        
        container.innerHTML = '';
        container.appendChild(errorElement);
    },
    
    // Show network error banner
    showNetworkBanner: function() {
        document.getElementById('network-error-banner').style.display = 'block';
    },
    
    // Hide network error banner
    hideNetworkBanner: function() {
        document.getElementById('network-error-banner').style.display = 'none';
    },
    
    // Handle different error types
    handleApiError: function(error, retryFunction = null) {
        if (error.status === 0) {
            this.showNetworkBanner();
            this.show('Bağlantı Hatası', 'İnternet bağlantınızı kontrol edin ve tekrar deneyin.', {
                type: 'network',
                retryFunction: retryFunction
            });
        } else if (error.status >= 500) {
            this.show('Sunucu Hatası', 'Sunucuda bir problem oluştu. Lütfen daha sonra tekrar deneyin.', {
                type: 'server',
                retryFunction: retryFunction
            });
        } else if (error.status === 404) {
            this.show('Bulunamadı', 'Aradığınız veri bulunamadı.', {
                retryFunction: retryFunction
            });
        } else {
            this.show('Hata', error.message || 'Beklenmeyen bir hata oluştu.', {
                details: error.stack,
                retryFunction: retryFunction
            });
        }
    },
    
    // Handle JavaScript errors
    handleJsError: function(error) {
        console.error('JavaScript Error:', error);
        
        this.show('Uygulama Hatası', 'Uygulamada bir hata oluştu.', {
            details: `${error.message}\n${error.stack}`,
            retryFunction: () => window.location.reload()
        });
    }
};

// Global error handlers
window.addEventListener('error', function(event) {
    ErrorComponent.handleJsError(event.error);
});

window.addEventListener('unhandledrejection', function(event) {
    ErrorComponent.handleJsError(event.reason);
});

// Network status monitoring
window.addEventListener('online', function() {
    ErrorComponent.hideNetworkBanner();
    ErrorComponent.showToast('Bağlantı yeniden kuruldu', 'success');
});

window.addEventListener('offline', function() {
    ErrorComponent.showNetworkBanner();
});

// Global shortcuts
window.showError = ErrorComponent.show;
window.showErrorToast = ErrorComponent.showToast;
window.handleApiError = ErrorComponent.handleApiError;
</script> 