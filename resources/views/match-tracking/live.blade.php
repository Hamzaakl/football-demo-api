@extends('layouts.app')

@section('title', 'Canlı Skorlar')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>
                    <span class="badge live-indicator text-white me-2">CANLI</span>
                    Canlı Skorlar
                </h1>
                <div>
                    <button class="btn btn-outline-primary me-2" onclick="toggleAutoRefresh()">
                        <i class="fas fa-sync-alt me-1" id="refresh-icon"></i>
                        <span id="refresh-text">Otomatik Yenileme: Açık</span>
                    </button>
                    <button class="btn btn-primary" onclick="refreshData()">
                        <i class="fas fa-refresh me-1"></i>Manual Yenile
                    </button>
                </div>
            </div>
            <p class="text-muted">Son güncelleme: <span id="last-update">Yükleniyor...</span></p>
        </div>
    </div>

    <!-- Live Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3 id="live-count">0</h3>
                <p class="mb-0">Canlı Maç</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3 id="total-goals">0</h3>
                <p class="mb-0">Toplam Gol</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3 id="finished-today">0</h3>
                <p class="mb-0">Bugün Biten</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3 id="upcoming-today">0</h3>
                <p class="mb-0">Başlayacak</p>
            </div>
        </div>
    </div>

    <!-- Live Matches Container -->
    <div id="live-matches-container">
        @if($liveMatches->count() > 0)
            <div class="row" id="matches-grid">
                @foreach($liveMatches as $match)
                <div class="col-md-6 col-lg-4 mb-4" data-fixture-id="{{ $match->id }}">
                    <div class="card match-card h-100">
                        <div class="card-body">
                            <!-- League Info -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted">{{ $match->league->name ?? 'Bilinmeyen Lig' }}</small>
                                <span class="badge bg-danger live-indicator pulse">
                                    {{ $match->status_turkish }} - {{ $match->elapsed }}'
                                </span>
                            </div>

                            <!-- Teams and Score -->
                            <div class="row align-items-center text-center mb-3">
                                <div class="col-4">
                                    @if($match->homeTeam->logo)
                                        <img src="{{ $match->homeTeam->logo }}" alt="{{ $match->homeTeam->name }}" class="team-logo mb-2">
                                    @else
                                        <div class="team-logo-placeholder mb-2">
                                            <i class="fas fa-shield-alt text-muted"></i>
                                        </div>
                                    @endif
                                    <h6 class="mb-0">{{ $match->homeTeam->name }}</h6>
                                </div>
                                
                                <div class="col-4">
                                    <div class="score-display live-score" data-home="{{ $match->home_goals ?? 0 }}" data-away="{{ $match->away_goals ?? 0 }}">
                                        {{ $match->home_goals ?? 0 }} - {{ $match->away_goals ?? 0 }}
                                    </div>
                                    <small class="text-muted d-block">{{ $match->elapsed }}'</small>
                                </div>
                                
                                <div class="col-4">
                                    @if($match->awayTeam->logo)
                                        <img src="{{ $match->awayTeam->logo }}" alt="{{ $match->awayTeam->name }}" class="team-logo mb-2">
                                    @else
                                        <div class="team-logo-placeholder mb-2">
                                            <i class="fas fa-shield-alt text-muted"></i>
                                        </div>
                                    @endif
                                    <h6 class="mb-0">{{ $match->awayTeam->name }}</h6>
                                </div>
                            </div>

                            <!-- Recent Events -->
                            @if($match->matchEvents->count() > 0)
                                <div class="recent-events mb-3">
                                    <h6 class="mb-2"><i class="fas fa-clock me-1"></i>Son Olaylar</h6>
                                    @foreach($match->matchEvents->take(3) as $event)
                                        <div class="event-item d-flex align-items-center mb-1">
                                            <span class="badge bg-primary me-2">{{ $event->formatted_elapsed }}</span>
                                            @if($event->type === 'Goal')
                                                <i class="fas fa-futbol text-success me-1"></i>
                                            @elseif($event->type === 'Card')
                                                <i class="fas fa-square text-warning me-1"></i>
                                            @else
                                                <i class="fas fa-info-circle text-muted me-1"></i>
                                            @endif
                                            <small>{{ $event->player_name }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Venue Info -->
                            @if($match->venue_name)
                                <div class="venue-info mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $match->venue_name }}
                                    </small>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('match-tracking.show', $match->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye me-1"></i>Detaylar
                                </a>
                                <button class="btn btn-sm btn-outline-primary" onclick="addToFavorites({{ $match->id }})">
                                    <i class="fas fa-heart me-1"></i>Favoriler
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Şu anda canlı maç yok</strong>
                <p class="mb-0">Canlı maç başladığında burada görünecektir.</p>
            </div>
        @endif
    </div>

    <!-- Quick Navigation -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <h5><i class="fas fa-compass me-2"></i>Hızlı Erişim</h5>
                    <div class="btn-group-wrap">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary me-2 mb-2">
                            <i class="fas fa-home me-1"></i>Ana Sayfa
                        </a>
                        <a href="{{ route('match-tracking.date', date('Y-m-d')) }}" class="btn btn-outline-primary me-2 mb-2">
                            <i class="fas fa-calendar me-1"></i>Bugünkü Maçlar
                        </a>
                        <a href="{{ route('match-tracking.date', date('Y-m-d', strtotime('+1 day'))) }}" class="btn btn-outline-primary me-2 mb-2">
                            <i class="fas fa-calendar-plus me-1"></i>Yarınki Maçlar
                        </a>
                        <a href="{{ route('match-tracking.search') }}" class="btn btn-outline-primary mb-2">
                            <i class="fas fa-search me-1"></i>Arama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    
    .live-score {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--danger-color);
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .team-logo-placeholder {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--light-color);
        border-radius: 50%;
        margin: 0 auto;
    }
    
    .recent-events {
        background: var(--light-color);
        border-radius: 8px;
        padding: 10px;
        max-height: 120px;
        overflow-y: auto;
    }
    
    .event-item {
        font-size: 0.8rem;
        line-height: 1.2;
    }
    
    .venue-info {
        border-top: 1px solid #eee;
        padding-top: 8px;
    }
    
    .btn-group-wrap {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .auto-refresh-disabled #refresh-icon {
        animation: none;
        opacity: 0.5;
    }
    
    .auto-refresh-enabled #refresh-icon {
        animation: spin 2s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .score-updated {
        animation: scoreFlash 1s ease-in-out;
    }
    
    @keyframes scoreFlash {
        0%, 100% { background: transparent; }
        50% { background: rgba(34, 197, 94, 0.2); border-radius: 4px; }
    }
</style>
@endpush

@push('scripts')
<script>
    let autoRefreshEnabled = true;
    let refreshInterval;
    let lastUpdateTime = null;

    // Sayfa yüklendiğinde
    document.addEventListener('DOMContentLoaded', function() {
        updateLastUpdateTime();
        startAutoRefresh();
        loadStats();
        
        // Her 30 saniyede bir otomatik yenile
        refreshInterval = setInterval(function() {
            if (autoRefreshEnabled) {
                refreshLiveData();
            }
        }, 30000);
    });

    // Son güncelleme zamanını göster
    function updateLastUpdateTime() {
        document.getElementById('last-update').textContent = new Date().toLocaleString('tr-TR');
    }

    // Otomatik yenileme toggle
    function toggleAutoRefresh() {
        autoRefreshEnabled = !autoRefreshEnabled;
        const text = document.getElementById('refresh-text');
        const icon = document.getElementById('refresh-icon');
        
        if (autoRefreshEnabled) {
            text.textContent = 'Otomatik Yenileme: Açık';
            document.body.classList.remove('auto-refresh-disabled');
            document.body.classList.add('auto-refresh-enabled');
        } else {
            text.textContent = 'Otomatik Yenileme: Kapalı';
            document.body.classList.remove('auto-refresh-enabled');
            document.body.classList.add('auto-refresh-disabled');
        }
    }

    // Manual yenileme
    function refreshData() {
        window.location.reload();
    }

    // Canlı veri yenileme
    function refreshLiveData() {
        fetch('/api/football/live-scores')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.live_fixtures) {
                    updateLiveMatches(data.data.live_fixtures);
                    updateLastUpdateTime();
                    loadStats();
                }
            })
            .catch(error => {
                console.error('Live data refresh error:', error);
            });
    }

    // Canlı maçları güncelle
    function updateLiveMatches(fixtures) {
        fixtures.forEach(function(fixture) {
            const matchCard = document.querySelector(`[data-fixture-id="${fixture.id}"]`);
            if (matchCard) {
                const scoreElement = matchCard.querySelector('.live-score');
                const statusBadge = matchCard.querySelector('.badge');
                
                if (scoreElement) {
                    const oldHome = scoreElement.dataset.home;
                    const oldAway = scoreElement.dataset.away;
                    const newHome = fixture.home_goals || 0;
                    const newAway = fixture.away_goals || 0;
                    
                    // Skor değiştiyse animasyon ekle
                    if (oldHome != newHome || oldAway != newAway) {
                        scoreElement.textContent = `${newHome} - ${newAway}`;
                        scoreElement.dataset.home = newHome;
                        scoreElement.dataset.away = newAway;
                        scoreElement.classList.add('score-updated');
                        
                        setTimeout(() => {
                            scoreElement.classList.remove('score-updated');
                        }, 1000);
                    }
                }
                
                if (statusBadge && fixture.elapsed) {
                    statusBadge.textContent = `${fixture.status_turkish} - ${fixture.elapsed}'`;
                }
            }
        });
    }

    // İstatistikleri yükle
    function loadStats() {
        const liveCount = document.querySelectorAll('[data-fixture-id]').length;
        document.getElementById('live-count').textContent = liveCount;
        
        // Toplam gol sayısı
        let totalGoals = 0;
        document.querySelectorAll('.live-score').forEach(function(scoreEl) {
            const home = parseInt(scoreEl.dataset.home) || 0;
            const away = parseInt(scoreEl.dataset.away) || 0;
            totalGoals += home + away;
        });
        document.getElementById('total-goals').textContent = totalGoals;
        
        // API'den ek istatistikler
        fetch('/api/football/todays-matches')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const matches = data.data;
                    const finished = matches.filter(m => m.is_finished).length;
                    const upcoming = matches.filter(m => !m.is_started && !m.is_finished).length;
                    
                    document.getElementById('finished-today').textContent = finished;
                    document.getElementById('upcoming-today').textContent = upcoming;
                }
            })
            .catch(error => console.error('Stats error:', error));
    }

    // Favorilere ekle
    function addToFavorites(fixtureId) {
        let favorites = JSON.parse(localStorage.getItem('favorite_matches') || '[]');
        
        if (!favorites.includes(fixtureId)) {
            favorites.push(fixtureId);
            localStorage.setItem('favorite_matches', JSON.stringify(favorites));
            
            // Success toast (basit alert)
            alert('Maç favorilere eklendi!');
        } else {
            alert('Bu maç zaten favorilerinizde!');
        }
    }

    // Echo ile gerçek zamanlı güncellemeler
    if (window.Echo) {
        window.Echo.channel('live-scores')
            .listen('.score.updated', (e) => {
                console.log('Score updated:', e);
                
                const matchCard = document.querySelector(`[data-fixture-id="${e.fixture_id}"]`);
                if (matchCard) {
                    const scoreElement = matchCard.querySelector('.live-score');
                    const statusBadge = matchCard.querySelector('.badge');
                    
                    if (scoreElement) {
                        scoreElement.textContent = `${e.home_goals || 0} - ${e.away_goals || 0}`;
                        scoreElement.dataset.home = e.home_goals || 0;
                        scoreElement.dataset.away = e.away_goals || 0;
                        scoreElement.classList.add('score-updated');
                        
                        setTimeout(() => {
                            scoreElement.classList.remove('score-updated');
                        }, 1000);
                    }
                    
                    if (statusBadge && e.elapsed) {
                        statusBadge.textContent = `${e.status_turkish} - ${e.elapsed}'`;
                    }
                    
                    // İstatistikleri güncelle
                    loadStats();
                }
            });
    }

    // Otomatik yenilemeyi başlat
    function startAutoRefresh() {
        document.body.classList.add('auto-refresh-enabled');
    }

    // Sayfa kapatılırken interval'ı temizle
    window.addEventListener('beforeunload', function() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    });
</script>
@endpush
@endsection 