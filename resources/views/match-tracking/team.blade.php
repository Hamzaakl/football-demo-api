@extends('layouts.app')

@section('title', $team->name)

@section('content')
<div class="container">
    <!-- Team Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            @if($team->logo)
                                <img src="{{ $team->logo }}" alt="{{ $team->name }}" 
                                     style="width: 120px; height: 120px; object-fit: contain;">
                            @else
                                <div class="team-logo-placeholder">
                                    <i class="fas fa-shield-alt text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h1 class="mb-2">{{ $team->name }}</h1>
                            @if($team->code)
                                <h5 class="text-muted mb-2">{{ $team->code }}</h5>
                            @endif
                            <p class="text-muted mb-2">
                                @if($team->country)
                                    <i class="fas fa-flag me-1"></i>{{ $team->country }}
                                @endif
                                @if($team->founded)
                                    <span class="ms-3"><i class="fas fa-calendar me-1"></i>Kuruluş: {{ $team->founded }}</span>
                                @endif
                            </p>
                            <div class="team-badges">
                                @if($team->national)
                                    <span class="badge bg-primary me-1">Milli Takım</span>
                                @else
                                    <span class="badge bg-success me-1">Kulüp Takımı</span>
                                @endif
                                @if($team->venue && isset($team->venue['name']))
                                    <span class="badge bg-info">{{ $team->venue['name'] }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2 text-end">
                            <button class="btn btn-outline-primary mb-2 w-100" onclick="toggleFavoriteTeam({{ $team->id }})">
                                <i class="fas fa-heart me-1"></i>
                                <span id="favorite-btn-text">Takip Et</span>
                            </button>
                            <button class="btn btn-outline-secondary w-100" onclick="shareTeam()">
                                <i class="fas fa-share-alt me-1"></i>Paylaş
                            </button>
                        </div>
                    </div>

                    <!-- Venue Info -->
                    @if($team->venue && (isset($team->venue['name']) || isset($team->venue['city'])))
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="venue-info p-3 bg-light rounded">
                                <h6><i class="fas fa-stadium me-1"></i>Stadyum Bilgileri</h6>
                                @if(isset($team->venue['name']))
                                    <p class="mb-1"><strong>Stadyum:</strong> {{ $team->venue['name'] }}</p>
                                @endif
                                @if(isset($team->venue['city']))
                                    <p class="mb-1"><strong>Şehir:</strong> {{ $team->venue['city'] }}</p>
                                @endif
                                @if(isset($team->venue['capacity']))
                                    <p class="mb-1"><strong>Kapasite:</strong> {{ number_format($team->venue['capacity']) }}</p>
                                @endif
                                @if(isset($team->venue['surface']))
                                    <p class="mb-0"><strong>Zemin:</strong> {{ $team->venue['surface'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Team Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3 id="total-matches">{{ $fixtures->total() }}</h3>
                <p class="mb-0">Toplam Maç</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3 id="wins">0</h3>
                <p class="mb-0">Galibiyet</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3 id="draws">0</h3>
                <p class="mb-0">Beraberlik</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3 id="losses">0</h3>
                <p class="mb-0">Mağlubiyet</p>
            </div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 id="goals-scored" class="text-success">0</h4>
                    <small class="text-muted">Atılan Gol</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 id="goals-conceded" class="text-danger">0</h4>
                    <small class="text-muted">Yenilen Gol</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 id="goal-difference" class="text-info">0</h4>
                    <small class="text-muted">Averaj</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 id="win-percentage" class="text-primary">0%</h4>
                    <small class="text-muted">Galibiyet Oranı</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <h5><i class="fas fa-bolt me-2"></i>Hızlı İşlemler</h5>
                    <div class="btn-group-wrap">
                        <button class="btn btn-outline-primary me-2 mb-2" onclick="showLiveMatches()">
                            <i class="fas fa-broadcast-tower me-1"></i>Canlı Maçlar
                        </button>
                        <button class="btn btn-outline-success me-2 mb-2" onclick="showUpcomingMatches()">
                            <i class="fas fa-calendar-plus me-1"></i>Gelecek Maçlar
                        </button>
                        <button class="btn btn-outline-info me-2 mb-2" onclick="showRecentResults()">
                            <i class="fas fa-history me-1"></i>Son Sonuçlar
                        </button>
                        <button class="btn btn-outline-warning mb-2" onclick="exportTeamData()">
                            <i class="fas fa-download me-1"></i>Verileri İndir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Match Filter Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-tabs" id="matchTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-matches" 
                            type="button" role="tab">
                        <i class="fas fa-list me-1"></i>Tüm Maçlar ({{ $fixtures->total() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-matches" 
                            type="button" role="tab">
                        <i class="fas fa-home me-1"></i>İç Saha (<span id="home-count">0</span>)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="away-tab" data-bs-toggle="tab" data-bs-target="#away-matches" 
                            type="button" role="tab">
                        <i class="fas fa-plane me-1"></i>Dış Saha (<span id="away-count">0</span>)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="live-tab" data-bs-toggle="tab" data-bs-target="#live-matches" 
                            type="button" role="tab">
                        <i class="fas fa-broadcast-tower me-1"></i>Canlı (<span id="live-count">0</span>)
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Matches Content -->
    <div class="row">
        <div class="col-12">
            <div class="tab-content" id="matchTabsContent">
                <!-- All Matches -->
                <div class="tab-pane fade show active" id="all-matches" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Tüm Maçlar</h5>
                            <div class="filter-controls">
                                <select class="form-select form-select-sm d-inline-block w-auto me-2" 
                                        onchange="filterMatches(this.value)">
                                    <option value="">Tüm Durumlar</option>
                                    <option value="live">Canlı</option>
                                    <option value="finished">Bitti</option>
                                    <option value="upcoming">Gelecek</option>
                                </select>
                                <button class="btn btn-sm btn-outline-primary" onclick="toggleView()">
                                    <i class="fas fa-th" id="view-icon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($fixtures->count() > 0)
                                <div class="matches-container" id="matches-list">
                                    @foreach($fixtures as $fixture)
                                    <div class="match-row {{ $fixture->is_live ? 'live-match' : '' }}" 
                                         data-match-status="{{ $fixture->status }}"
                                         data-is-home="{{ $fixture->home_team_id == $team->id ? 'true' : 'false' }}">
                                        <div class="row align-items-center py-3">
                                            <div class="col-md-2 text-center">
                                                <div class="match-date">
                                                    <small class="d-block text-muted">{{ $fixture->fixture_date->format('d.m.Y') }}</small>
                                                    <strong>{{ $fixture->fixture_date->format('H:i') }}</strong>
                                                </div>
                                                @if($fixture->is_live)
                                                    <div class="live-dot"></div>
                                                @endif
                                            </div>
                                            <div class="col-md-3">
                                                <div class="team-info {{ $fixture->home_team_id == $team->id ? 'current-team' : '' }}">
                                                    @if($fixture->homeTeam->logo)
                                                        <img src="{{ $fixture->homeTeam->logo }}" alt="{{ $fixture->homeTeam->name }}" 
                                                             class="team-logo-small me-2">
                                                    @endif
                                                    <span>{{ $fixture->homeTeam->name }}</span>
                                                    @if($fixture->home_team_id == $team->id)
                                                        <i class="fas fa-home ms-1 text-primary"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                @if($fixture->is_started)
                                                    <div class="match-score">
                                                        <strong>{{ $fixture->home_goals ?? 0 }} - {{ $fixture->away_goals ?? 0 }}</strong>
                                                        @if($fixture->is_live)
                                                            <div class="small text-muted">{{ $fixture->elapsed }}'</div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="badge bg-secondary">{{ $fixture->status_turkish }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-3">
                                                <div class="team-info {{ $fixture->away_team_id == $team->id ? 'current-team' : '' }}">
                                                    @if($fixture->away_team_id == $team->id)
                                                        <i class="fas fa-plane me-1 text-info"></i>
                                                    @endif
                                                    <span>{{ $fixture->awayTeam->name }}</span>
                                                    @if($fixture->awayTeam->logo)
                                                        <img src="{{ $fixture->awayTeam->logo }}" alt="{{ $fixture->awayTeam->name }}" 
                                                             class="team-logo-small ms-2">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <div class="match-result-indicator">
                                                    @if($fixture->is_finished)
                                                        @php
                                                            $result = '';
                                                            if ($fixture->home_team_id == $team->id) {
                                                                if ($fixture->home_goals > $fixture->away_goals) $result = 'win';
                                                                elseif ($fixture->home_goals < $fixture->away_goals) $result = 'loss';
                                                                else $result = 'draw';
                                                            } else {
                                                                if ($fixture->away_goals > $fixture->home_goals) $result = 'win';
                                                                elseif ($fixture->away_goals < $fixture->home_goals) $result = 'loss';
                                                                else $result = 'draw';
                                                            }
                                                        @endphp
                                                        @if($result == 'win')
                                                            <span class="badge bg-success">G</span>
                                                        @elseif($result == 'loss')
                                                            <span class="badge bg-danger">M</span>
                                                        @else
                                                            <span class="badge bg-warning">B</span>
                                                        @endif
                                                    @endif
                                                </div>
                                                <a href="{{ route('match-tracking.show', $fixture->id) }}" 
                                                   class="btn btn-sm btn-outline-primary mt-1">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- League Info -->
                                        <div class="row">
                                            <div class="col-12">
                                                <small class="text-muted ms-3">
                                                    <i class="fas fa-trophy me-1"></i>{{ $fixture->league->name ?? 'Bilinmeyen Lig' }}
                                                    @if($fixture->venue_name)
                                                        <span class="ms-2">
                                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $fixture->venue_name }}
                                                        </span>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5>Bu takım için henüz maç verisi yok</h5>
                                    <p class="text-muted">Maç verileri güncellendiğinde burada görünecektir.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Home Matches -->
                <div class="tab-pane fade" id="home-matches" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-center text-muted">İç saha maçları filtrelemesi JavaScript ile yapılacak.</p>
                        </div>
                    </div>
                </div>

                <!-- Away Matches -->
                <div class="tab-pane fade" id="away-matches" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-center text-muted">Dış saha maçları filtrelemesi JavaScript ile yapılacak.</p>
                        </div>
                    </div>
                </div>

                <!-- Live Matches -->
                <div class="tab-pane fade" id="live-matches" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-center text-muted">Canlı maçlar filtrelemesi JavaScript ile yapılacak.</p>
                        </div>
                    </div>
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
    .team-logo-placeholder {
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--light-color);
        border-radius: 15px;
        margin: 0 auto;
    }

    .team-logo-placeholder i {
        font-size: 3rem;
    }

    .team-badges .badge {
        font-size: 0.8rem;
    }

    .venue-info {
        border-left: 4px solid var(--primary-color);
    }

    .match-row {
        border-bottom: 1px solid #eee;
        transition: all 0.2s;
        position: relative;
    }

    .match-row:hover {
        background-color: var(--light-color);
    }

    .match-row.live-match {
        background: linear-gradient(90deg, rgba(239, 68, 68, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
        border-left: 4px solid var(--danger-color);
    }

    .team-info {
        display: flex;
        align-items: center;
    }

    .team-info.current-team {
        font-weight: 600;
        color: var(--primary-color);
    }

    .team-logo-small {
        width: 25px;
        height: 25px;
        object-fit: contain;
    }

    .match-score {
        font-size: 1.1rem;
        font-weight: 700;
    }

    .live-dot {
        width: 8px;
        height: 8px;
        background: var(--danger-color);
        border-radius: 50%;
        margin: 4px auto;
        animation: pulse 2s infinite;
    }

    .match-result-indicator .badge {
        width: 25px;
        height: 25px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .match-date strong {
        font-size: 1rem;
    }

    .nav-tabs .nav-link {
        color: var(--dark-color);
        border: none;
        border-bottom: 3px solid transparent;
    }

    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        background: none;
        border-bottom-color: var(--primary-color);
    }

    .btn-group-wrap {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.5rem;
    }

    .filter-controls {
        display: flex;
        align-items: center;
    }

    .matches-container {
        max-height: 600px;
        overflow-y: auto;
    }

    @media (max-width: 768px) {
        .match-row .col-md-3,
        .match-row .col-md-2 {
            margin-bottom: 0.5rem;
        }
        
        .team-info {
            justify-content: center;
            margin-bottom: 0.25rem;
        }
        
        .match-result-indicator {
            text-align: center;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let teamStats = {
        wins: 0,
        draws: 0,
        losses: 0,
        goalsScored: 0,
        goalsConceded: 0,
        homeMatches: 0,
        awayMatches: 0,
        liveMatches: 0
    };

    // Sayfa yüklendiğinde
    document.addEventListener('DOMContentLoaded', function() {
        calculateTeamStats();
        checkFavoriteStatus();
        initializeTabs();
    });

    // Takım istatistiklerini hesapla
    function calculateTeamStats() {
        const matchRows = document.querySelectorAll('.match-row');
        
        matchRows.forEach(function(row) {
            const isHome = row.dataset.isHome === 'true';
            const status = row.dataset.matchStatus;
            
            // İç/Dış saha sayısı
            if (isHome) {
                teamStats.homeMatches++;
            } else {
                teamStats.awayMatches++;
            }
            
            // Canlı maç sayısı
            if (row.classList.contains('live-match')) {
                teamStats.liveMatches++;
            }
            
            // Sonuç analizi (sadece biten maçlar için)
            const resultBadge = row.querySelector('.match-result-indicator .badge');
            if (resultBadge) {
                const result = resultBadge.textContent.trim();
                if (result === 'G') teamStats.wins++;
                else if (result === 'M') teamStats.losses++;
                else if (result === 'B') teamStats.draws++;
            }
            
            // Gol istatistikleri
            const scoreElement = row.querySelector('.match-score strong');
            if (scoreElement && status === 'FT') {
                const scoreText = scoreElement.textContent.trim();
                const [homeGoals, awayGoals] = scoreText.split(' - ').map(Number);
                
                if (isHome) {
                    teamStats.goalsScored += homeGoals;
                    teamStats.goalsConceded += awayGoals;
                } else {
                    teamStats.goalsScored += awayGoals;
                    teamStats.goalsConceded += homeGoals;
                }
            }
        });
        
        // İstatistikleri güncelle
        updateStatsDisplay();
    }

    // İstatistikleri görüntüle
    function updateStatsDisplay() {
        document.getElementById('wins').textContent = teamStats.wins;
        document.getElementById('draws').textContent = teamStats.draws;
        document.getElementById('losses').textContent = teamStats.losses;
        document.getElementById('goals-scored').textContent = teamStats.goalsScored;
        document.getElementById('goals-conceded').textContent = teamStats.goalsConceded;
        
        const goalDifference = teamStats.goalsScored - teamStats.goalsConceded;
        const goalDiffElement = document.getElementById('goal-difference');
        goalDiffElement.textContent = goalDifference > 0 ? '+' + goalDifference : goalDifference;
        goalDiffElement.className = goalDifference > 0 ? 'text-success' : goalDifference < 0 ? 'text-danger' : 'text-muted';
        
        const totalMatches = teamStats.wins + teamStats.draws + teamStats.losses;
        const winPercentage = totalMatches > 0 ? Math.round((teamStats.wins / totalMatches) * 100) : 0;
        document.getElementById('win-percentage').textContent = winPercentage + '%';
        
        // Tab sayılarını güncelle
        document.getElementById('home-count').textContent = teamStats.homeMatches;
        document.getElementById('away-count').textContent = teamStats.awayMatches;
        document.getElementById('live-count').textContent = teamStats.liveMatches;
    }

    // Favori durumunu kontrol et
    function checkFavoriteStatus() {
        const favoriteTeams = JSON.parse(localStorage.getItem('favorite_teams') || '[]');
        const teamId = {{ $team->id }};
        const btnText = document.getElementById('favorite-btn-text');
        
        if (favoriteTeams.includes(teamId)) {
            btnText.textContent = 'Takipten Çık';
        }
    }

    // Favori takım toggle
    function toggleFavoriteTeam(teamId) {
        let favoriteTeams = JSON.parse(localStorage.getItem('favorite_teams') || '[]');
        const btnText = document.getElementById('favorite-btn-text');
        
        if (favoriteTeams.includes(teamId)) {
            favoriteTeams = favoriteTeams.filter(id => id !== teamId);
            btnText.textContent = 'Takip Et';
            showNotification('Takım takipten çıkarıldı!', 'info');
        } else {
            favoriteTeams.push(teamId);
            btnText.textContent = 'Takipten Çık';
            showNotification('Takım favorilere eklendi!', 'success');
        }
        
        localStorage.setItem('favorite_teams', JSON.stringify(favoriteTeams));
    }

    // Takımı paylaş
    function shareTeam() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $team->name }} - Football Live',
                url: window.location.href
            });
        } else {
            // Fallback: Link kopyala
            navigator.clipboard.writeText(window.location.href).then(function() {
                showNotification('Link kopyalandı!', 'success');
            });
        }
    }

    // Tab'ları başlat
    function initializeTabs() {
        // Tab değişimlerini dinle
        const tabs = document.querySelectorAll('[data-bs-toggle="tab"]');
        tabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                const target = e.target.getAttribute('data-bs-target');
                filterMatchesByTab(target);
            });
        });
    }

    // Tab'a göre maçları filtrele
    function filterMatchesByTab(tabTarget) {
        const allMatches = document.querySelectorAll('.match-row');
        
        allMatches.forEach(function(match) {
            let show = true;
            const isHome = match.dataset.isHome === 'true';
            const isLive = match.classList.contains('live-match');
            
            switch(tabTarget) {
                case '#home-matches':
                    show = isHome;
                    break;
                case '#away-matches':
                    show = !isHome;
                    break;
                case '#live-matches':
                    show = isLive;
                    break;
                case '#all-matches':
                default:
                    show = true;
                    break;
            }
            
            match.style.display = show ? 'block' : 'none';
        });
    }

    // Hızlı filtreler
    function showLiveMatches() {
        document.getElementById('live-tab').click();
    }

    function showUpcomingMatches() {
        filterMatches('upcoming');
    }

    function showRecentResults() {
        filterMatches('finished');
    }

    // Maçları filtrele
    function filterMatches(status) {
        const matches = document.querySelectorAll('.match-row');
        
        matches.forEach(function(match) {
            let show = true;
            const matchStatus = match.dataset.matchStatus;
            const isLive = match.classList.contains('live-match');
            
            if (status === 'live' && !isLive) show = false;
            if (status === 'finished' && matchStatus !== 'FT') show = false;
            if (status === 'upcoming' && (isLive || matchStatus === 'FT')) show = false;
            
            match.style.display = show ? 'block' : 'none';
        });
    }

    // Görünümü değiştir
    function toggleView() {
        const icon = document.getElementById('view-icon');
        const container = document.getElementById('matches-list');
        
        if (icon.classList.contains('fa-th')) {
            // Grid view'a geç
            icon.classList.remove('fa-th');
            icon.classList.add('fa-list');
            container.classList.add('grid-view');
        } else {
            // List view'a geç
            icon.classList.remove('fa-list');
            icon.classList.add('fa-th');
            container.classList.remove('grid-view');
        }
    }

    // Takım verilerini dışa aktar
    function exportTeamData() {
        const teamData = {
            team: '{{ $team->name }}',
            stats: teamStats,
            exportDate: new Date().toISOString()
        };
        
        const blob = new Blob([JSON.stringify(teamData, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${teamData.team}_stats.json`;
        a.click();
        URL.revokeObjectURL(url);
    }

    // Bildirim göster
    function showNotification(message, type = 'info') {
        // Basit alert - gerçek projede toast notification kullanılabilir
        alert(message);
    }

    // Canlı güncellemeler için WebSocket bağlantısı
    if (window.Echo) {
        // Takım maçları kanalını dinle
        window.Echo.channel('team.{{ $team->id }}')
            .listen('.match.updated', (e) => {
                console.log('Team match updated:', e);
                // Maç bilgilerini güncelle
                updateMatchInList(e.fixture);
            });
    }

    // Liste içinde maçı güncelle
    function updateMatchInList(fixture) {
        const matchRow = document.querySelector(`[data-match-id="${fixture.id}"]`);
        if (matchRow) {
            // Skoru güncelle
            const scoreElement = matchRow.querySelector('.match-score strong');
            if (scoreElement) {
                scoreElement.textContent = `${fixture.home_goals || 0} - ${fixture.away_goals || 0}`;
            }
            
            // Canlı durumu güncelle
            if (fixture.is_live && !matchRow.classList.contains('live-match')) {
                matchRow.classList.add('live-match');
                teamStats.liveMatches++;
                updateStatsDisplay();
            }
        }
    }
</script>
@endpush
@endsection 