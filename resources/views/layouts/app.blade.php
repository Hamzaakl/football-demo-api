<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Canlı Futbol Takip') - Football Live</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --success-color: #22c55e;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-color);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }

        .match-card {
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
        }

        .match-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .live-indicator {
            background: linear-gradient(45deg, #ef4444, #dc2626);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .score-display {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .team-logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .chat-container {
            height: 400px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .chat-messages {
            height: 300px;
            overflow-y: auto;
        }

        .message-bubble {
            background: var(--light-color);
            border-radius: 12px;
            padding: 8px 12px;
            margin: 4px 0;
        }

        .system-message {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            text-align: center;
            font-weight: 500;
        }

        .sidebar {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-futbol me-2"></i>
                Football Live
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>Ana Sayfa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('match-tracking.live') }}">
                            <i class="fas fa-broadcast-tower me-1"></i>Canlı Skorlar
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-trophy me-1"></i>Ligler
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-flag me-2"></i>Süper Lig</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-flag me-2"></i>Premier League</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-flag me-2"></i>La Liga</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-flag me-2"></i>Bundesliga</a></li>
                        </ul>
                    </li>
                </ul>
                
                <form class="d-flex" action="{{ route('match-tracking.search') }}" method="GET">
                    <input class="form-control me-2" type="search" name="q" placeholder="Takım ara..." value="{{ request('q') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-section">
        <!-- Main Footer -->
        <div class="footer-main">
            <div class="container">
                <div class="row">
                    <!-- Brand Section -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="footer-brand">
                            <h4 class="footer-logo">
                                <i class="fas fa-futbol me-2 rotating-logo"></i>
                                Football Live
                            </h4>
                            <p class="footer-description">
                                Dünyanın en güncel canlı futbol skorları, maç istatistikleri ve 
                                taraftar sohbet platformu. Futbol tutkunu her anında yanınızda.
                            </p>
                            <div class="footer-stats">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <h6 id="footer-leagues">200+</h6>
                                            <small>Lig</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <h6 id="footer-teams">5000+</h6>
                                            <small>Takım</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <h6 id="footer-users">10K+</h6>
                                            <small>Kullanıcı</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h5 class="footer-title">Hızlı Erişim</h5>
                        <ul class="footer-links">
                            <li><a href="{{ route('home') }}"><i class="fas fa-home me-2"></i>Ana Sayfa</a></li>
                            <li><a href="{{ route('match-tracking.live') }}"><i class="fas fa-broadcast-tower me-2"></i>Canlı Skorlar</a></li>
                            <li><a href="{{ route('match-tracking.date', date('Y-m-d')) }}"><i class="fas fa-calendar-day me-2"></i>Bugünkü Maçlar</a></li>
                            <li><a href="{{ route('match-tracking.search') }}"><i class="fas fa-search me-2"></i>Ara</a></li>
                            <li><a href="{{ route('match-tracking.date', date('Y-m-d', strtotime('+1 day'))) }}"><i class="fas fa-calendar-plus me-2"></i>Yarın</a></li>
                        </ul>
                    </div>

                    <!-- Popular Leagues -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h5 class="footer-title">Popüler Ligler</h5>
                        <ul class="footer-links">
                            <li><a href="{{ route('match-tracking.search') }}?q=Süper Lig"><i class="fas fa-flag me-2"></i>Süper Lig</a></li>
                            <li><a href="{{ route('match-tracking.search') }}?q=Premier League"><i class="fas fa-flag me-2"></i>Premier League</a></li>
                            <li><a href="{{ route('match-tracking.search') }}?q=La Liga"><i class="fas fa-flag me-2"></i>La Liga</a></li>
                            <li><a href="{{ route('match-tracking.search') }}?q=Serie A"><i class="fas fa-flag me-2"></i>Serie A</a></li>
                            <li><a href="{{ route('match-tracking.search') }}?q=Bundesliga"><i class="fas fa-flag me-2"></i>Bundesliga</a></li>
                            <li><a href="{{ route('match-tracking.search') }}?q=Champions League"><i class="fas fa-trophy me-2"></i>Champions League</a></li>
                        </ul>
                    </div>

                    <!-- Features -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h5 class="footer-title">Özellikler</h5>
                        <ul class="footer-links">
                            <li><i class="fas fa-check text-success me-2"></i>Canlı Skorlar</li>
                            <li><i class="fas fa-check text-success me-2"></i>Maç İstatistikleri</li>
                            <li><i class="fas fa-check text-success me-2"></i>Canlı Sohbet</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gerçek Zamanlı Bildirimler</li>
                            <li><i class="fas fa-check text-success me-2"></i>Takım Takibi</li>
                            <li><i class="fas fa-check text-success me-2"></i>Maç Arşivi</li>
                        </ul>
                    </div>

                    <!-- Contact & Social -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <h5 class="footer-title">İletişim & Sosyal</h5>
                        
                        <!-- Newsletter -->
                        <div class="newsletter-section mb-3">
                            <p class="small">Önemli maçları kaçırmayın!</p>
                            <form class="newsletter-form" onsubmit="subscribeNewsletter(event)">
                                <div class="input-group">
                                    <input type="email" class="form-control" placeholder="Email adresiniz" required>
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Social Media -->
                        <div class="social-media">
                            <h6 class="mb-3">Sosyal Medya</h6>
                            <div class="social-links">
                                <a href="#" class="social-link facebook" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="social-link twitter" title="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="social-link instagram" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="social-link youtube" title="YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a href="#" class="social-link telegram" title="Telegram">
                                    <i class="fab fa-telegram"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="contact-info mt-3">
                            <div class="contact-item">
                                <i class="fas fa-envelope me-2"></i>
                                <span>info@footballlive.com</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone me-2"></i>
                                <span>+90 (212) 123 45 67</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-8 mb-3 mb-md-0">
                        <div class="footer-copyright">
                            <p class="mb-0">
                                &copy; {{ date('Y') }} <strong>Football Live</strong>. Tüm hakları saklıdır.
                            </p>
                            <div class="footer-legal">
                                <a href="#" onclick="showModal('privacy')">Gizlilik Politikası</a>
                                <span class="separator">|</span>
                                <a href="#" onclick="showModal('terms')">Kullanım Koşulları</a>
                                <span class="separator">|</span>
                                <a href="#" onclick="showModal('cookies')">Çerez Politikası</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4">
                        <div class="footer-tech text-end">
                            <div class="tech-stack">
                                <small class="text-muted">Güçlü Teknolojiler:</small>
                                <div class="tech-badges mt-1">
                                    <span class="tech-badge">Laravel</span>
                                    <span class="tech-badge">Bootstrap</span>
                                    <span class="tech-badge">WebSocket</span>
                                    <span class="tech-badge">RapidAPI</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Live Status -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="live-status-bar">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="status-left">
                                    <div class="live-indicator-dot"></div>
                                    <span id="footer-live-status">Sistem Aktif</span>
                                    <small class="ms-2" id="footer-last-update">Son güncelleme: {{ now()->format('H:i') }}</small>
                                </div>
                                <div class="status-right">
                                    <button class="btn btn-sm btn-outline-light" onclick="checkSystemStatus()">
                                        <i class="fas fa-sync-alt me-1"></i>Durum Kontrolü
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Footer Styles -->
    <style>
        .footer-section {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            color: #f9fafb;
            position: relative;
            overflow: hidden;
        }

        .footer-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), #22c55e);
        }

        .footer-main {
            padding: 3rem 0 2rem;
        }

        .footer-brand .rotating-logo {
            animation: rotate 8s linear infinite;
            color: var(--primary-color);
        }

        .footer-logo {
            color: #f9fafb;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-description {
            color: #d1d5db;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .footer-stats .stat-item h6 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0;
        }

        .footer-title {
            color: #f9fafb;
            font-weight: 600;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 30px;
            height: 2px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: #d1d5db;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .footer-links a:hover {
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .footer-links a i {
            width: 16px;
            opacity: 0.7;
        }

        .newsletter-form .form-control {
            border-radius: 6px 0 0 6px;
            border: 1px solid #4b5563;
            background: rgba(55, 65, 81, 0.5);
            color: #f9fafb;
        }

        .newsletter-form .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
            background: rgba(55, 65, 81, 0.8);
            color: #f9fafb;
        }

        .newsletter-form .btn {
            border-radius: 0 6px 6px 0;
        }

        .social-links {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f9fafb;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .social-link.facebook { background: #1877f2; }
        .social-link.twitter { background: #1da1f2; }
        .social-link.instagram { background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); }
        .social-link.youtube { background: #ff0000; }
        .social-link.telegram { background: #0088cc; }

        .social-link:hover {
            transform: translateY(-3px);
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .contact-info {
            font-size: 0.9rem;
        }

        .contact-item {
            color: #d1d5db;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .contact-item i {
            color: var(--primary-color);
            width: 20px;
        }

        .footer-bottom {
            background: rgba(0, 0, 0, 0.3);
            padding: 1.5rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-copyright p {
            margin: 0;
            color: #d1d5db;
        }

        .footer-legal {
            margin-top: 0.5rem;
        }

        .footer-legal a {
            color: #9ca3af;
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s ease;
        }

        .footer-legal a:hover {
            color: var(--primary-color);
        }

        .separator {
            margin: 0 0.5rem;
            color: #6b7280;
        }

        .tech-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .tech-badge {
            background: rgba(37, 99, 235, 0.2);
            color: var(--primary-color);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .live-status-bar {
            background: rgba(0, 0, 0, 0.2);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .live-indicator-dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
            animation: pulse 2s infinite;
        }

        @media (max-width: 768px) {
            .footer-main {
                padding: 2rem 0 1rem;
            }

            .footer-title::after {
                width: 20px;
            }

            .tech-badges {
                justify-content: flex-start;
                margin-top: 1rem;
            }

            .footer-tech {
                text-align: left !important;
            }

            .status-right {
                margin-top: 1rem;
            }

            .live-status-bar .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
            }
        }
    </style>

    <!-- Footer JavaScript -->
    <script>
        // Newsletter subscription
        function subscribeNewsletter(event) {
            event.preventDefault();
            const email = event.target.querySelector('input[type="email"]').value;
            
            if (email) {
                // Simulate newsletter subscription
                console.log('Newsletter subscription:', email);
                alert('Teşekkürler! Newsletter aboneliğiniz başarıyla oluşturuldu.');
                event.target.reset();
                
                // Real implementation would send to backend
                // fetch('/api/newsletter/subscribe', { ... })
            }
        }

        // System status check
        function checkSystemStatus() {
            const statusEl = document.getElementById('footer-live-status');
            const updateEl = document.getElementById('footer-last-update');
            
            statusEl.textContent = 'Kontrol ediliyor...';
            
            setTimeout(() => {
                statusEl.textContent = 'Sistem Aktif';
                updateEl.textContent = 'Son güncelleme: ' + new Date().toLocaleTimeString('tr-TR', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }, 1000);
        }

        // Show modal for legal pages
        function showModal(type) {
            const titles = {
                privacy: 'Gizlilik Politikası',
                terms: 'Kullanım Koşulları',
                cookies: 'Çerez Politikası'
            };
            
            const content = {
                privacy: 'Gizlilik politikamız yakında yayınlanacak...',
                terms: 'Kullanım koşullarımız yakında yayınlanacak...',
                cookies: 'Çerez politikamız yakında yayınlanacak...'
            };
            
            alert(`${titles[type]}\n\n${content[type]}`);
            // Real implementation would show a proper modal
        }

        // Update footer stats periodically
        function updateFooterStats() {
            // Simulate real-time stats
            const leagues = document.getElementById('footer-leagues');
            const teams = document.getElementById('footer-teams');
            const users = document.getElementById('footer-users');
            
            if (leagues) leagues.textContent = (200 + Math.floor(Math.random() * 50)) + '+';
            if (teams) teams.textContent = (5000 + Math.floor(Math.random() * 1000)) + '+';
            if (users) users.textContent = (10000 + Math.floor(Math.random() * 5000)) + '+';
        }

        // Initialize footer
        document.addEventListener('DOMContentLoaded', function() {
            updateFooterStats();
            setInterval(updateFooterStats, 60000); // Update every minute
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Laravel Echo & Pusher -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.js"></script>

    <script>
        // CSRF Token setup
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };

        // Echo setup (will be configured later with proper Pusher credentials)
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ config("services.pusher.key") }}',
            cluster: '{{ config("services.pusher.cluster") }}',
            forceTLS: true
        });
    </script>

    @stack('scripts')
    
    <!-- Include Loading and Error Components -->
    @include('components.loading')
    @include('components.error')
</body>
</html> 