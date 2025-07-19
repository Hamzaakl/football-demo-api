@extends('layouts.app')

@section('title', '404 - Sayfa Bulunamadı')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body text-center py-5">
                    <!-- 404 Animation -->
                    <div class="error-animation mb-4">
                        <div class="football-404">
                            <i class="fas fa-futbol fa-6x text-primary mb-3 rotating-ball"></i>
                            <h1 class="display-1 text-primary mb-0">404</h1>
                        </div>
                    </div>
                    
                    <!-- Error Message -->
                    <div class="error-message mb-5">
                        <h2 class="h3 mb-3">Oops! Sayfa Bulunamadı</h2>
                        <p class="text-muted mb-4">
                            Aradığınız sayfa mevcut değil veya taşınmış olabilir.<br>
                            Belki de top saha dışına çıktı! ⚽
                        </p>
                        
                        <!-- Fun Football Messages -->
                        <div class="football-messages">
                            <div class="alert alert-light border">
                                <div id="random-message" class="fw-bold text-primary"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="action-buttons mb-4">
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg me-3 mb-2">
                            <i class="fas fa-home me-2"></i>Ana Sayfaya Dön
                        </a>
                        <button onclick="history.back()" class="btn btn-outline-secondary btn-lg me-3 mb-2">
                            <i class="fas fa-arrow-left me-2"></i>Geri Dön
                        </button>
                        <a href="{{ route('match-tracking.search') }}" class="btn btn-outline-primary btn-lg mb-2">
                            <i class="fas fa-search me-2"></i>Arama Yap
                        </a>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="quick-links">
                        <h5 class="mb-3">Popüler Sayfalar</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="list-group">
                                    <a href="{{ route('match-tracking.live') }}" class="list-group-item list-group-item-action">
                                        <i class="fas fa-broadcast-tower me-2 text-danger"></i>
                                        <strong>Canlı Skorlar</strong>
                                        <small class="d-block text-muted">Devam eden maçları takip edin</small>
                                    </a>
                                    <a href="{{ route('match-tracking.date', date('Y-m-d')) }}" class="list-group-item list-group-item-action">
                                        <i class="fas fa-calendar-day me-2 text-primary"></i>
                                        <strong>Bugünkü Maçlar</strong>
                                        <small class="d-block text-muted">{{ date('d.m.Y') }} maçlarını görün</small>
                                    </a>
                                    <a href="{{ route('match-tracking.search') }}" class="list-group-item list-group-item-action">
                                        <i class="fas fa-search me-2 text-success"></i>
                                        <strong>Takım/Lig Ara</strong>
                                        <small class="d-block text-muted">Favori takımınızı bulun</small>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-lightbulb me-2"></i>Belki bunlar işinize yarar?
                                        </h6>
                                        <div class="suggestion-buttons">
                                            <button class="btn btn-sm btn-outline-primary me-1 mb-1" onclick="goToSearch('Galatasaray')">
                                                Galatasaray
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary me-1 mb-1" onclick="goToSearch('Fenerbahçe')">
                                                Fenerbahçe
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary me-1 mb-1" onclick="goToSearch('Beşiktaş')">
                                                Beşiktaş
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary me-1 mb-1" onclick="goToSearch('Premier League')">
                                                Premier League
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary me-1 mb-1" onclick="goToSearch('Champions League')">
                                                Champions League
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Fun Stats -->
                    <div class="fun-stats mt-4">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="stat-item">
                                    <i class="fas fa-futbol text-primary fa-2x mb-2"></i>
                                    <h4 id="goals-counter">0</h4>
                                    <small class="text-muted">Dünyada atılan goller</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="stat-item">
                                    <i class="fas fa-users text-success fa-2x mb-2"></i>
                                    <h4 id="fans-counter">0</h4>
                                    <small class="text-muted">Futbol severlerin sayısı</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="stat-item">
                                    <i class="fas fa-heart text-danger fa-2x mb-2"></i>
                                    <h4 id="passion-counter">∞</h4>
                                    <small class="text-muted">Futbol aşkımız</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Report Problem -->
                    <div class="report-section mt-4 pt-4 border-top">
                        <p class="text-muted mb-2">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Sürekli bu hatayla karşılaşıyorsanız, lütfen bize bildirin.
                        </p>
                        <button class="btn btn-sm btn-outline-warning" onclick="reportProblem()">
                            <i class="fas fa-bug me-2"></i>Sorunu Bildir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .rotating-ball {
        animation: rotate 4s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .football-404 {
        position: relative;
        display: inline-block;
    }
    
    .football-404 h1 {
        position: relative;
        z-index: 2;
        text-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
    }
    
    .football-404 i {
        position: absolute;
        top: -30px;
        right: -20px;
        z-index: 1;
        opacity: 0.1;
    }
    
    .error-animation {
        margin: 2rem 0;
    }
    
    .football-messages .alert {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px dashed var(--primary-color);
        animation: fadeInMessage 2s ease-in-out;
    }
    
    @keyframes fadeInMessage {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    .stat-item {
        padding: 1rem;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }
    
    .stat-item:hover {
        transform: translateY(-5px);
        background: rgba(37, 99, 235, 0.1);
    }
    
    .list-group-item {
        border: none;
        border-radius: 8px !important;
        margin-bottom: 0.5rem;
        transition: all 0.2s;
    }
    
    .list-group-item:hover {
        background-color: var(--primary-color);
        color: white;
        transform: translateX(5px);
    }
    
    .list-group-item:hover small {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    
    .suggestion-buttons .btn {
        transition: all 0.2s;
    }
    
    .suggestion-buttons .btn:hover {
        transform: scale(1.05);
    }
    
    .action-buttons .btn {
        min-width: 160px;
    }
    
    @media (max-width: 768px) {
        .football-404 i {
            font-size: 4rem !important;
            top: -20px;
            right: -10px;
        }
        
        .action-buttons .btn {
            min-width: auto;
            width: 100%;
            margin-bottom: 0.5rem !important;
        }
        
        .display-1 {
            font-size: 4rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Eğlenceli futbol mesajları
    const footballMessages = [
        "Bu sayfa kırmızı kart gördü ve oyun dışı kaldı! 🔴",
        "Sayfa ofsayta takıldı, VAR inceleme yapıyor... 🎥",
        "Bu URL golden sonra sahayı terk etti! ⚽",
        "Hakem bu sayfayı iptal etti, tekrar deneyin! 🥅",
        "Sayfa penaltı noktasına gitti ama bulamadık! 🎯",
        "Bu link corner'a gitti, geri dönmedi! 🚩",
        "Sayfa devre arasında kayboldu! ⏰",
        "Bu URL transfer oldu, yeni adresi aranıyor! 📋",
        "Sayfa sakatlık nedeniyle oyundan çıktı! 🏥",
        "Link müdahale gördü, oyun durdu! ⚠️"
    ];
    
    // Animasyon sayaçları
    let goalsCount = 0;
    let fansCount = 0;
    
    // Sayfa yüklendiğinde
    document.addEventListener('DOMContentLoaded', function() {
        showRandomMessage();
        startCounters();
        trackError();
    });
    
    // Rastgele mesaj göster
    function showRandomMessage() {
        const randomIndex = Math.floor(Math.random() * footballMessages.length);
        const messageElement = document.getElementById('random-message');
        
        messageElement.style.opacity = '0';
        
        setTimeout(() => {
            messageElement.textContent = footballMessages[randomIndex];
            messageElement.style.opacity = '1';
        }, 300);
        
        // 5 saniyede bir mesajı değiştir
        setTimeout(showRandomMessage, 5000);
    }
    
    // Sayaçları başlat
    function startCounters() {
        // Gol sayacı
        const goalsTarget = Math.floor(Math.random() * 1000000) + 500000;
        const goalsInterval = setInterval(() => {
            goalsCount += Math.floor(Math.random() * 100) + 10;
            document.getElementById('goals-counter').textContent = goalsCount.toLocaleString();
            
            if (goalsCount >= goalsTarget) {
                clearInterval(goalsInterval);
            }
        }, 100);
        
        // Taraftar sayacı  
        const fansTarget = Math.floor(Math.random() * 10000000) + 5000000;
        const fansInterval = setInterval(() => {
            fansCount += Math.floor(Math.random() * 1000) + 100;
            document.getElementById('fans-counter').textContent = fansCount.toLocaleString();
            
            if (fansCount >= fansTarget) {
                clearInterval(fansInterval);
            }
        }, 150);
    }
    
    // Arama sayfasına git
    function goToSearch(term) {
        const searchUrl = `{{ route('match-tracking.search') }}?q=${encodeURIComponent(term)}`;
        window.location.href = searchUrl;
    }
    
    // Sorunu bildir
    function reportProblem() {
        const problemUrl = window.location.href;
        const userAgent = navigator.userAgent;
        const timestamp = new Date().toISOString();
        
        const reportData = {
            url: problemUrl,
            userAgent: userAgent,
            timestamp: timestamp,
            error: '404 - Page Not Found'
        };
        
        // Gerçek projede bu bilgiler sunucuya gönderilebilir
        console.log('Problem reported:', reportData);
        
        alert('Teşekkürler! Sorunu aldık ve en kısa sürede çözeceğiz. 🛠️');
    }
    
    // 404 hatası tracking (analytics için)
    function trackError() {
        // Google Analytics veya başka bir analytics servisine gönderilebilir
        if (typeof gtag !== 'undefined') {
            gtag('event', 'page_not_found', {
                'page_location': window.location.href,
                'page_referrer': document.referrer
            });
        }
        
        console.log('404 Error tracked:', {
            url: window.location.href,
            referrer: document.referrer,
            timestamp: new Date().toISOString()
        });
    }
    
    // Easter egg: Konami code
    let konamiCode = [];
    const konamiSequence = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65]; // Up Up Down Down Left Right Left Right B A
    
    document.addEventListener('keydown', function(e) {
        konamiCode.push(e.keyCode);
        
        if (konamiCode.length > konamiSequence.length) {
            konamiCode.shift();
        }
        
        if (konamiCode.length === konamiSequence.length && 
            konamiCode.every((code, index) => code === konamiSequence[index])) {
            
            // Easter egg aktif!
            showEasterEgg();
            konamiCode = [];
        }
    });
    
    // Easter egg göster
    function showEasterEgg() {
        const easterEgg = document.createElement('div');
        easterEgg.innerHTML = `
            <div class="alert alert-success mt-4 text-center">
                <h4>🎉 Gizli Kod Bulundu! 🎉</h4>
                <p>Konami Code'u buldunuz! Gerçek bir futbol fanısınız! ⚽</p>
                <button class="btn btn-success" onclick="this.parentElement.remove()">
                    <i class="fas fa-trophy me-2"></i>Harika!
                </button>
            </div>
        `;
        
        document.querySelector('.card-body').appendChild(easterEgg);
        
        // Konfeti efekti (basit)
        createConfetti();
    }
    
    // Basit konfeti efekti
    function createConfetti() {
        for (let i = 0; i < 30; i++) {
            setTimeout(() => {
                const confetti = document.createElement('div');
                confetti.style.cssText = `
                    position: fixed;
                    top: -10px;
                    left: ${Math.random() * 100}%;
                    width: 10px;
                    height: 10px;
                    background: ${['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#ffeaa7'][Math.floor(Math.random() * 5)]};
                    transform: rotate(${Math.random() * 360}deg);
                    animation: confetti-fall 3s linear forwards;
                    z-index: 9999;
                `;
                
                document.body.appendChild(confetti);
                
                setTimeout(() => confetti.remove(), 3000);
            }, i * 100);
        }
    }
    
    // Konfeti animasyonu
    const style = document.createElement('style');
    style.textContent = `
        @keyframes confetti-fall {
            to {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Klavye kısayolları
    document.addEventListener('keydown', function(e) {
        // H tuşu: Ana sayfaya git
        if (e.key === 'h' || e.key === 'H') {
            if (e.target.tagName !== 'INPUT') {
                window.location.href = '{{ route("home") }}';
            }
        }
        
        // S tuşu: Arama sayfasına git
        if (e.key === 's' || e.key === 'S') {
            if (e.target.tagName !== 'INPUT') {
                e.preventDefault();
                window.location.href = '{{ route("match-tracking.search") }}';
            }
        }
    });
</script>
@endpush
@endsection 