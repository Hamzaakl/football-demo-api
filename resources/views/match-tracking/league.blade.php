@extends('layouts.app')

@section('title', $league->name)

@section('content')
<div class="container">
    <!-- League Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            @if($league->logo)
                                <img src="{{ $league->logo }}" alt="{{ $league->name }}" 
                                     style="width: 100px; height: 100px; object-fit: contain;">
                            @else
                                <div class="league-logo-placeholder">
                                    <i class="fas fa-trophy text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h1 class="mb-1">{{ $league->name }}</h1>
                            <p class="text-muted mb-2">
                                <i class="fas fa-flag me-1"></i>{{ $league->country }}
                                @if($league->season)
                                    <span class="ms-3"><i class="fas fa-calendar me-1"></i>{{ $league->season }} Sezonu</span>
                                @endif
                            </p>
                            @if($league->type)
                                <span class="badge bg-primary">{{ $league->type }}</span>
                            @endif
                            @if($league->current)
                                <span class="badge bg-success ms-1">Aktif Sezon</span>
                            @endif
                        </div>
                        <div class="col-md-2 text-end">
                            <button class="btn btn-outline-primary mb-2" onclick="toggleFavoriteLeague({{ $league->id }})">
                                <i class="fas fa-heart me-1"></i>Takip Et
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-share-alt me-1"></i>Paylaş
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="shareLeague('twitter')">
                                        <i class="fab fa-twitter me-2"></i>Twitter
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="shareLeague('facebook')">
                                        <i class="fab fa-facebook me-2"></i>Facebook
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="copyLeagueUrl()">
                                        <i class="fas fa-copy me-2"></i>Link Kopyala
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- League Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3 id="total-matches">{{ $fixtures->total() }}</h3>
                <p class="mb-0">Toplam Maç</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3 id="live-matches">0</h3>
                <p class="mb-0">Canlı Maç</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3 id="completed-matches">0</h3>
                <p class="mb-0">Tamamlanan</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3 id="upcoming-matches">0</h3>
                <p class="mb-0">Gelecek Maçlar</p>
            </div>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5><i class="fas fa-filter me-2"></i>Filtreler</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <select class="form-select" id="status-filter" onchange="filterMatches()">
                                <option value="">Tüm Durum</option>
                                <option value="live">Canlı</option>
                                <option value="finished">Tamamlandı</option>
                                <option value="upcoming">Gelecek</option>
                                <option value="postponed">Ertelendi</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="date-filter" onchange="filterMatches()">
                                <option value="">Tüm Zamanlar</option>
                                <option value="today">Bugün</option>
                                <option value="yesterday">Dün</option>
                                <option value="week">Bu Hafta</option>
                                <option value="month">Bu Ay</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="team-search" 
                                   placeholder="Takım ara..." oninput="filterMatches()">
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex">
                                <button class="btn btn-outline-secondary me-2" onclick="clearFilters()">
                                    <i class="fas fa-times me-1"></i>Temizle
                                </button>
                                <button class="btn btn-primary" onclick="exportMatches()">
                                    <i class="fas fa-download me-1"></i>Dışa Aktar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Matches List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Maçlar</h5>
                    <div class="view-toggle">
                        <button class="btn btn-sm btn-outline-primary active" onclick="switchView('list')" id="list-view-btn">
                            <i class="fas fa-list"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-primary ms-1" onclick="switchView('grid')" id="grid-view-btn">
                            <i class="fas fa-th"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($fixtures->count() > 0)
                        <!-- List View -->
                        <div id="list-view" class="matches-list">
                            @foreach($fixtures as $fixture)
                            <div class="match-item {{ $fixture->is_live ? 'live' : '' }}" 
                                 data-fixture="{{ json_encode([
                                     'id' => $fixture->id,
                                     'status' => $fixture->status,
                                     'date' => $fixture->fixture_date->format('Y-m-d'),
                                     'home_team' => $fixture->homeTeam->name,
                                     'away_team' => $fixture->awayTeam->name,
                                     'is_live' => $fixture->is_live,
                                     'is_finished' => $fixture->is_finished
                                 ]) }}">
                                <div class="row align-items-center py-3">
                                    <div class="col-md-2 text-center">
                                        <small class="text-muted d-block">{{ $fixture->fixture_date->format('d.m.Y') }}</small>
                                        <strong>{{ $fixture->fixture_date->format('H:i') }}</strong>
                                        @if($fixture->is_live)
                                            <div class="live-indicator-small"></div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            @if($fixture->homeTeam->logo)
                                                <img src="{{ $fixture->homeTeam->logo }}" alt="{{ $fixture->homeTeam->name }}" 
                                                     class="team-logo-small me-2">
                                            @endif
                                            <span class="fw-bold">{{ $fixture->homeTeam->name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        @if($fixture->is_started)
                                            <div class="score-display">
                                                {{ $fixture->home_goals ?? 0 }} - {{ $fixture->away_goals ?? 0 }}
                                            </div>
                                            @if($fixture->is_live)
                                                <small class="text-muted">{{ $fixture->elapsed }}'</small>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">{{ $fixture->status_turkish }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <span class="fw-bold me-2">{{ $fixture->awayTeam->name }}</span>
                                            @if($fixture->awayTeam->logo)
                                                <img src="{{ $fixture->awayTeam->logo }}" alt="{{ $fixture->awayTeam->name }}" 
                                                     class="team-logo-small">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-end">
                                        <a href="{{ route('match-tracking.show', $fixture->id) }}" 
                                           class="btn btn-sm btn-outline-primary me-2">
                                            <i class="fas fa-eye me-1"></i>Detaylar
                                        </a>
                                        @if($fixture->is_live)
                                            <span class="badge bg-danger">CANLI</span>
                                        @elseif($fixture->is_finished)
                                            <span class="badge bg-success">BİTTİ</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $fixture->status_turkish }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Grid View (Hidden by default) -->
                        <div id="grid-view" class="matches-grid" style="display: none;">
                            <div class="row p-3">
                                @foreach($fixtures as $fixture)
                                <div class="col-md-6 col-lg-4 mb-3 match-grid-item" 
                                     data-fixture="{{ json_encode([
                                         'id' => $fixture->id,
                                         'status' => $fixture->status,
                                         'date' => $fixture->fixture_date->format('Y-m-d'),
                                         'home_team' => $fixture->homeTeam->name,
                                         'away_team' => $fixture->awayTeam->name,
                                         'is_live' => $fixture->is_live,
                                         'is_finished' => $fixture->is_finished
                                     ]) }}">
                                    <div class="card match-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <small class="text-muted">{{ $fixture->fixture_date->format('d.m.Y H:i') }}</small>
                                                @if($fixture->is_live)
                                                    <span class="badge bg-danger live-indicator">CANLI</span>
                                                @elseif($fixture->is_finished)
                                                    <span class="badge bg-success">BİTTİ</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $fixture->status_turkish }}</span>
                                                @endif
                                            </div>
                                            
                                            <div class="row align-items-center text-center">
                                                <div class="col-4">
                                                    @if($fixture->homeTeam->logo)
                                                        <img src="{{ $fixture->homeTeam->logo }}" alt="{{ $fixture->homeTeam->name }}" 
                                                             class="team-logo mb-2">
                                                    @endif
                                                    <h6 class="mb-0 small">{{ $fixture->homeTeam->name }}</h6>
                                                </div>
                                                
                                                <div class="col-4">
                                                    @if($fixture->is_started)
                                                        <div class="score-display">
                                                            {{ $fixture->home_goals ?? 0 }} - {{ $fixture->away_goals ?? 0 }}
                                                        </div>
                                                        @if($fixture->is_live)
                                                            <small class="text-muted">{{ $fixture->elapsed }}'</small>
                                                        @endif
                                                    @else
                                                        <div class="text-muted">VS</div>
                                                    @endif
                                                </div>
                                                
                                                <div class="col-4">
                                                    @if($fixture->awayTeam->logo)
                                                        <img src="{{ $fixture->awayTeam->logo }}" alt="{{ $fixture->awayTeam->name }}" 
                                                             class="team-logo mb-2">
                                                    @endif
                                                    <h6 class="mb-0 small">{{ $fixture->awayTeam->name }}</h6>
                                                </div>
                                            </div>
                                            
                                            <div class="text-center mt-3">
                                                <a href="{{ route('match-tracking.show', $fixture->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye me-1"></i>Detaylar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5>Bu ligde henüz maç yok</h5>
                            <p class="text-muted">Maçlar programlandığında burada görünecektir.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($fixtures->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $fixtures->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    .league-logo-placeholder {
        width: 100px;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--light-color);
        border-radius: 12px;
        margin: 0 auto;
    }

    .league-logo-placeholder i {
        font-size: 2rem;
    }

    .match-item {
        border-bottom: 1px solid #eee;
        transition: background-color 0.2s;
    }

    .match-item:hover {
        background-color: var(--light-color);
    }

    .match-item.live {
        background: linear-gradient(90deg, rgba(239, 68, 68, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
        border-left: 4px solid var(--danger-color);
    }

    .team-logo-small {
        width: 30px;
        height: 30px;
        object-fit: contain;
    }

    .live-indicator-small {
        width: 8px;
        height: 8px;
        background: var(--danger-color);
        border-radius: 50%;
        margin: 4px auto;
        animation: pulse 2s infinite;
    }

    .view-toggle .btn.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .matches-list {
        max-height: 800px;
        overflow-y: auto;
    }

    .score-display {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dark-color);
    }

    .match-grid-item .team-logo {
        width: 35px;
        height: 35px;
        object-fit: contain;
    }

    .match-grid-item .card-body {
        padding: 1rem 0.8rem;
    }

    .match-grid-item h6 {
        font-size: 0.8rem;
        line-height: 1.2;
    }
</style>
@endpush

@push('scripts')
<script>
    // Sayfa yüklendiğinde istatistikleri hesapla
    document.addEventListener('DOMContentLoaded', function() {
        calculateStats();
        loadLiveMatches();
    });

    // İstatistikleri hesapla
    function calculateStats() {
        const allMatches = document.querySelectorAll('.match-item, .match-grid-item');
        let liveCount = 0;
        let completedCount = 0;
        let upcomingCount = 0;

        allMatches.forEach(function(match) {
            const fixtureData = JSON.parse(match.dataset.fixture);
            
            if (fixtureData.is_live) {
                liveCount++;
            } else if (fixtureData.is_finished) {
                completedCount++;
            } else {
                upcomingCount++;
            }
        });

        document.getElementById('live-matches').textContent = liveCount;
        document.getElementById('completed-matches').textContent = completedCount;
        document.getElementById('upcoming-matches').textContent = upcomingCount;
    }

    // Canlı maçları yükle
    function loadLiveMatches() {
        // Her 30 saniyede bir canlı maç durumunu kontrol et
        setInterval(function() {
            updateLiveMatchesStatus();
        }, 30000);
    }

    // Canlı maç durumlarını güncelle
    function updateLiveMatchesStatus() {
        fetch(`/api/football/league/{{ $league->id }}/matches`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.data) {
                    // Canlı durumları güncelle (basit implementasyon)
                    console.log('League matches updated');
                }
            })
            .catch(error => console.error('Live update error:', error));
    }

    // Görünüm değiştir (List/Grid)
    function switchView(view) {
        const listView = document.getElementById('list-view');
        const gridView = document.getElementById('grid-view');
        const listBtn = document.getElementById('list-view-btn');
        const gridBtn = document.getElementById('grid-view-btn');

        if (view === 'list') {
            listView.style.display = 'block';
            gridView.style.display = 'none';
            listBtn.classList.add('active');
            gridBtn.classList.remove('active');
        } else {
            listView.style.display = 'none';
            gridView.style.display = 'block';
            listBtn.classList.remove('active');
            gridBtn.classList.add('active');
        }

        // Tercihi kaydet
        localStorage.setItem('league_view_preference', view);
    }

    // Maçları filtrele
    function filterMatches() {
        const statusFilter = document.getElementById('status-filter').value;
        const dateFilter = document.getElementById('date-filter').value;
        const teamSearch = document.getElementById('team-search').value.toLowerCase();

        const matches = document.querySelectorAll('.match-item, .match-grid-item');
        
        matches.forEach(function(match) {
            const fixtureData = JSON.parse(match.dataset.fixture);
            let show = true;

            // Durum filtresi
            if (statusFilter) {
                if (statusFilter === 'live' && !fixtureData.is_live) show = false;
                if (statusFilter === 'finished' && !fixtureData.is_finished) show = false;
                if (statusFilter === 'upcoming' && (fixtureData.is_live || fixtureData.is_finished)) show = false;
                if (statusFilter === 'postponed' && fixtureData.status !== 'PST') show = false;
            }

            // Tarih filtresi
            if (dateFilter && show) {
                const matchDate = new Date(fixtureData.date);
                const today = new Date();
                
                if (dateFilter === 'today' && matchDate.toDateString() !== today.toDateString()) show = false;
                if (dateFilter === 'yesterday') {
                    const yesterday = new Date(today);
                    yesterday.setDate(yesterday.getDate() - 1);
                    if (matchDate.toDateString() !== yesterday.toDateString()) show = false;
                }
                // Diğer tarih filtreleri eklenebilir
            }

            // Takım arama
            if (teamSearch && show) {
                const homeTeam = fixtureData.home_team.toLowerCase();
                const awayTeam = fixtureData.away_team.toLowerCase();
                if (!homeTeam.includes(teamSearch) && !awayTeam.includes(teamSearch)) {
                    show = false;
                }
            }

            match.style.display = show ? 'block' : 'none';
        });
    }

    // Filtreleri temizle
    function clearFilters() {
        document.getElementById('status-filter').value = '';
        document.getElementById('date-filter').value = '';
        document.getElementById('team-search').value = '';
        filterMatches();
    }

    // Ligi favorilere ekle/çıkar
    function toggleFavoriteLeague(leagueId) {
        let favoriteLeagues = JSON.parse(localStorage.getItem('favorite_leagues') || '[]');
        
        if (favoriteLeagues.includes(leagueId)) {
            favoriteLeagues = favoriteLeagues.filter(id => id !== leagueId);
            alert('Lig favorilerden çıkarıldı!');
        } else {
            favoriteLeagues.push(leagueId);
            alert('Lig favorilere eklendi!');
        }
        
        localStorage.setItem('favorite_leagues', JSON.stringify(favoriteLeagues));
    }

    // Ligi paylaş
    function shareLeague(platform) {
        const url = window.location.href;
        const title = `{{ $league->name }} - Football Live`;
        
        let shareUrl = '';
        
        if (platform === 'twitter') {
            shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`;
        } else if (platform === 'facebook') {
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
        }
        
        if (shareUrl) {
            window.open(shareUrl, '_blank', 'width=600,height=400');
        }
    }

    // Link kopyala
    function copyLeagueUrl() {
        navigator.clipboard.writeText(window.location.href).then(function() {
            alert('Link kopyalandı!');
        });
    }

    // Maçları dışa aktar
    function exportMatches() {
        alert('Dışa aktarma özelliği geliştiriliyor...');
        // CSV/Excel export implementasyonu eklenebilir
    }

    // Sayfa yüklendiğinde görünüm tercihini yükle
    document.addEventListener('DOMContentLoaded', function() {
        const viewPreference = localStorage.getItem('league_view_preference') || 'list';
        switchView(viewPreference);
    });
</script>
@endpush
@endsection 