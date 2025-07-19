<!-- Loading Component -->
<div id="loading-overlay" class="loading-overlay" style="display: none;">
    <div class="loading-content">
        <div class="loading-spinner">
            <div class="football-spinner">
                <i class="fas fa-futbol rotating-ball"></i>
            </div>
        </div>
        <div class="loading-text mt-3">
            <h5 id="loading-title">Yükleniyor...</h5>
            <p id="loading-message" class="text-muted mb-0">Lütfen bekleyin</p>
        </div>
        <div class="loading-progress mt-3">
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                     role="progressbar" id="loading-progress-bar" 
                     style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inline Loading (for smaller areas) -->
<div class="inline-loading text-center py-4" style="display: none;" id="inline-loading">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Yükleniyor...</span>
    </div>
    <div class="mt-2">
        <small class="text-muted">Veriler yükleniyor...</small>
    </div>
</div>

<!-- Skeleton Loading (for match cards) -->
<div class="skeleton-loading" style="display: none;" id="skeleton-loading">
    <div class="row">
        <div class="col-md-6 col-lg-4 mb-3" v-for="i in 6">
            <div class="card match-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="skeleton-text skeleton-small"></div>
                        <div class="skeleton-badge"></div>
                    </div>
                    
                    <div class="row align-items-center text-center mb-3">
                        <div class="col-4">
                            <div class="skeleton-circle mx-auto mb-2"></div>
                            <div class="skeleton-text"></div>
                        </div>
                        <div class="col-4">
                            <div class="skeleton-score mb-2"></div>
                            <div class="skeleton-text skeleton-small"></div>
                        </div>
                        <div class="col-4">
                            <div class="skeleton-circle mx-auto mb-2"></div>
                            <div class="skeleton-text"></div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="skeleton-button"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Loading Overlay Styles */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(5px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.loading-content {
    text-align: center;
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    min-width: 300px;
}

.football-spinner {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.rotating-ball {
    color: var(--primary-color);
    animation: rotate 2s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.loading-progress .progress {
    height: 8px;
    background-color: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
}

.loading-progress .progress-bar {
    background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
    transition: width 0.3s ease;
}

/* Skeleton Loading Styles */
.skeleton-loading {
    animation: pulse 1.5s ease-in-out infinite;
}

.skeleton-text,
.skeleton-circle,
.skeleton-badge,
.skeleton-button,
.skeleton-score {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
    border-radius: 4px;
}

.skeleton-text {
    height: 16px;
    width: 100%;
    margin-bottom: 8px;
}

.skeleton-text.skeleton-small {
    height: 12px;
    width: 60%;
}

.skeleton-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.skeleton-badge {
    height: 20px;
    width: 60px;
    border-radius: 10px;
}

.skeleton-button {
    height: 32px;
    width: 80px;
    border-radius: 6px;
    margin: 0 auto;
}

.skeleton-score {
    height: 24px;
    width: 60px;
    margin: 0 auto;
    font-weight: bold;
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

/* Inline Loading Styles */
.inline-loading {
    min-height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .loading-content {
        min-width: 280px;
        padding: 1.5rem;
    }
    
    .football-spinner {
        font-size: 2.5rem;
    }
    
    .skeleton-loading .col-md-6,
    .skeleton-loading .col-lg-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
</style>

<script>
// Loading Component JavaScript
window.LoadingComponent = {
    // Overlay Loading
    showOverlay: function(title = 'Yükleniyor...', message = 'Lütfen bekleyin') {
        document.getElementById('loading-title').textContent = title;
        document.getElementById('loading-message').textContent = message;
        document.getElementById('loading-overlay').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    },
    
    hideOverlay: function() {
        document.getElementById('loading-overlay').style.display = 'none';
        document.body.style.overflow = '';
        this.resetProgress();
    },
    
    // Progress Bar
    updateProgress: function(percent) {
        const progressBar = document.getElementById('loading-progress-bar');
        progressBar.style.width = percent + '%';
        progressBar.setAttribute('aria-valuenow', percent);
    },
    
    resetProgress: function() {
        this.updateProgress(0);
    },
    
    // Inline Loading
    showInline: function(containerId) {
        const container = document.getElementById(containerId);
        const inlineLoading = document.getElementById('inline-loading').cloneNode(true);
        inlineLoading.id = 'inline-loading-' + Date.now();
        inlineLoading.style.display = 'block';
        
        container.innerHTML = '';
        container.appendChild(inlineLoading);
    },
    
    hideInline: function(containerId) {
        const container = document.getElementById(containerId);
        const loadingElements = container.querySelectorAll('[id^="inline-loading-"]');
        loadingElements.forEach(element => element.remove());
    },
    
    // Skeleton Loading
    showSkeleton: function(containerId) {
        const container = document.getElementById(containerId);
        const skeletonLoading = document.getElementById('skeleton-loading').cloneNode(true);
        skeletonLoading.id = 'skeleton-loading-' + Date.now();
        skeletonLoading.style.display = 'block';
        
        container.innerHTML = '';
        container.appendChild(skeletonLoading);
    },
    
    hideSkeleton: function(containerId) {
        const container = document.getElementById(containerId);
        const skeletonElements = container.querySelectorAll('[id^="skeleton-loading-"]');
        skeletonElements.forEach(element => element.remove());
    },
    
    // Auto-hide with timeout
    showWithTimeout: function(title, message, timeout = 5000) {
        this.showOverlay(title, message);
        
        setTimeout(() => {
            this.hideOverlay();
        }, timeout);
    },
    
    // Show loading with progress simulation
    showWithProgress: function(title, message, duration = 3000) {
        this.showOverlay(title, message);
        
        let progress = 0;
        const interval = 50;
        const increment = 100 / (duration / interval);
        
        const progressInterval = setInterval(() => {
            progress += increment;
            this.updateProgress(Math.min(progress, 100));
            
            if (progress >= 100) {
                clearInterval(progressInterval);
                setTimeout(() => {
                    this.hideOverlay();
                }, 500);
            }
        }, interval);
    }
};

// Global shortcuts
window.showLoading = window.LoadingComponent.showOverlay;
window.hideLoading = window.LoadingComponent.hideOverlay;
</script> 