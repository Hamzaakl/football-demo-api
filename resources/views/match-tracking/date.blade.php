@extends('layouts.app')

@section('title', $matchDate->format('d.m.Y') . ' Maçları')

@section('content')
<div class="container">
    <!-- Date Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-2">
                                <i class="fas fa-calendar-day me-2"></i>
                                {{ $matchDate->format('d F Y') }} Maçları
                            </h1>
                            <p class="text-muted mb-0">
                                {{ $matchDate->locale('tr')->dayName }}, 
                                @if($matchDate->isToday())
                                    <span class="badge bg-success">Bugün</span>
                                @elseif($matchDate->isYesterday())
                                    <span class="badge bg-info">Dün</span>
                                @elseif($matchDate->isTomorrow())
                                    <span class="badge bg-warning">Yarın</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="date-navigation">
                                <a href="{{ route('match-tracking.date', $matchDate->copy()->subDay()->format('Y-m-d')) }}" 
                                   class="btn btn-outline-primary me-2">
                                    <i class="fas fa-chevron-left me-1"></i>Önceki Gün
                                </a>
                                <a href="{{ route('match-tracking.date', $matchDate->copy()->addDay()->format('Y-m-d')) }}" 
                                   class="btn btn-outline-primary">
                                    Sonraki Gün<i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                            <div class="mt-2">
                                <input type="date" class="form-control" id="date-picker" 
                                       value="{{ $matchDate->format('Y-m-d') }}" 
                                       onchange="goToDate(this.value)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3 id="total-matches">{{ $fixtures->count() }}</h3>
                <p class="mb-0">Toplam Maç</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3 id="live-matches">0</h3>
                <p class="mb-0">Canlı</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3 id="finished-matches">0</h3>
                <p class="mb-0">Bitti</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h3 id="upcoming-matches">0</h3>
                <p class="mb-0">Başlayacak</p>
            </div>
        </div>
    </div>

    <!-- Quick Date Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <h5><i class="fas fa-calendar me-2"></i>Hızlı Tarih Seçimi</h5>
                    <div class="quick-dates">
                        <a href="{{ route('match-tracking.date', now()->subDays(2)->format('Y-m-d')) }}" 
                           class="btn btn-outline-secondary me-2 mb-2">
                            {{ now()->subDays(2)->format('d.m') }}
                        </a>
                        <a href="{{ route('match-tracking.date', now()->subDay()->format('Y-m-d')) }}" 
                           class="btn btn-outline-info me-2 mb-2">
                            Dün
                        </a>
                        <a href="{{ route('match-tracking.date', now()->format('Y-m-d')) }}" 
                           class="btn btn-primary me-2 mb-2">
                            Bugün
                        </a>
                        <a href="{{ route('match-tracking.date', now()->addDay()->format('Y-m-d')) }}" 
                           class="btn btn-outline-warning me-2 mb-2">
                            Yarın
                        </a>
                        <a href="{{ route('match-tracking.date', now()->addDays(2)->format('Y-m-d')) }}" 
                           class="btn btn-outline-secondary mb-2">
                            {{ now()->addDays(2)->format('d.m') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Lig Filtresi</label>
                            <select class="form-select" id="league-filter" onchange="filterMatches()">
                                <option value="">Tüm Ligler</option>
                                <!-- Ligler JavaScript ile doldurulacak -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Durum Filtresi</label>
                            <select class="form-select" id="status-filter" onchange="filterMatches()">
                                <option value="">Tüm Durumlar</option>
                                <option value="live">Canlı</option>
                                <option value="finished">Bitti</option>
                                <option value="upcoming">Başlayacak</option>
                                <option value="postponed">Ertelendi</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Takım Ara</label>
                            <input type="text" class="form-control" id="team-search" 
                                   placeholder="Takım adı..." oninput="filterMatches()">
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary" onclick="clearFilters()">
                                    <i class="fas fa-times me-1"></i>Temizle
                                </button>
                                <button class="btn btn-primary" onclick="toggleView()">
                                    <i class="fas fa-th" id="view-toggle-icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Matches Content -->
    @if($fixtures->count() > 0)
        <!-- Time-based Groups -->
        <div id="matches-container">
            @php
                $groupedMatches = $fixtures->groupBy(function($fixture) {
                    return $fixture->fixture_date->format('H:00');
                });
                $groupedMatches = $groupedMatches->sortKeys();
            @endphp

            @foreach($groupedMatches as $timeSlot => $matchesInSlot)
            <div class="row mb-4 time-group" data-time="{{ $timeSlot }}">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-clock me-2"></i>{{ $timeSlot }}
                                    <span class="badge bg-primary ms-2">{{ $matchesInSlot->count() }} maç</span>
                                </h5>
                                <div class="time-stats">
                                    <small class="text-muted">
                                        <span class="live-count-{{ $timeSlot }}">0</span> canlı,
                                        <span class="finished-count-{{ $timeSlot }}">0</span> bitti
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="matches-list" id="list-view-{{ $timeSlot }}">
                                @foreach($matchesInSlot as $fixture)
                                <div class="match-item {{ $fixture->is_live ? 'live' : '' }}" 
                                     data-fixture-id="{{ $fixture->id }}"
                                     data-league="{{ $fixture->league->name ?? 'Unknown' }}"
                                     data-status="{{ $fixture->status }}"
                                     data-home-team="{{ $fixture->homeTeam->name }}"
                                     data-away-team="{{ $fixture->awayTeam->name }}">
                                    <div class="row align-items-center py-3">
                                        <div class="col-md-1 text-center">
                                            <div class="time-display">
                                                <strong>{{ $fixture->fixture_date->format('H:i') }}</strong>
                                                @if($fixture->is_live)
                                                    <div class="live-dot"></div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <small class="text-muted league-name">{{ $fixture->league->name ?? 'Bilinmeyen Lig' }}</small>
                                            @if($fixture->league && $fixture->league->logo)
                                                <img src="{{ $fixture->league->logo }}" alt="{{ $fixture->league->name }}" 
                                                     class="league-logo-small d-block mx-auto mt-1">
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <div class="team-info home-team">
                                                @if($fixture->homeTeam->logo)
                                                    <img src="{{ $fixture->homeTeam->logo }}" alt="{{ $fixture->homeTeam->name }}" 
                                                         class="team-logo-small me-2">
                                                @endif
                                                <span class="fw-bold">{{ $fixture->homeTeam->name }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <div class="match-result">
                                                @if($fixture->is_started)
                                                    <div class="score-display">
                                                        <strong>{{ $fixture->home_goals ?? 0 }} - {{ $fixture->away_goals ?? 0 }}</strong>
                                                    </div>
                                                    @if($fixture->is_live)
                                                        <small class="text-danger">{{ $fixture->elapsed }}'</small>
                                                    @elseif($fixture->is_finished)
                                                        <small class="text-success">MS</small>
                                                    @endif
                                                @else
                                                    <span class="vs-text">VS</span>
                                                    <div class="mt-1">
                                                        @if($fixture->status === 'PST')
                                                            <span class="badge bg-warning">Ertelendi</span>
                                                        @elseif($fixture->status === 'CANC')
                                                            <span class="badge bg-danger">İptal</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $fixture->status_turkish }}</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="team-info away-team justify-content-end">
                                                <span class="fw-bold">{{ $fixture->awayTeam->name }}</span>
                                                @if($fixture->awayTeam->logo)
                                                    <img src="{{ $fixture->awayTeam->logo }}" alt="{{ $fixture->awayTeam->name }}" 
                                                         class="team-logo-small ms-2">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <a href="{{ route('match-tracking.show', $fixture->id) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <!-- Additional Info -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="additional-info px-3 pb-2">
                                                @if($fixture->venue_name)
                                                    <small class="text-muted me-3">
                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $fixture->venue_name }}
                                                    </small>
                                                @endif
                                                @if($fixture->referee)
                                                    <small class="text-muted">
                                                        <i class="fas fa-whistle me-1"></i>{{ $fixture->referee }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Grid View (Hidden by default) -->
        <div id="grid-view" style="display: none;">
            <div class="row">
                @foreach($fixtures as $fixture)
                <div class="col-md-6 col-lg-4 mb-3 match-grid-item"
                     data-fixture-id="{{ $fixture->id }}"
                     data-league="{{ $fixture->league->name ?? 'Unknown' }}"
                     data-status="{{ $fixture->status }}"
                     data-home-team="{{ $fixture->homeTeam->name }}"
                     data-away-team="{{ $fixture->awayTeam->name }}">
                    <div class="card match-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">{{ $fixture->fixture_date->format('H:i') }}</small>
                                @if($fixture->is_live)
                                    <span class="badge bg-danger live-indicator">CANLI</span>
                                @elseif($fixture->is_finished)
                                    <span class="badge bg-success">BİTTİ</span>
                                @else
                                    <span class="badge bg-secondary">{{ $fixture->status_turkish }}</span>
                                @endif
                            </div>
                            
                            <div class="text-center mb-2">
                                <small class="text-muted">{{ $fixture->league->name ?? 'Bilinmeyen Lig' }}</small>
                            </div>
                            
                            <div class="row align-items-center text-center">
                                <div class="col-4">
                                    @if($fixture->homeTeam->logo)
                                        <img src="{{ $fixture->homeTeam->logo }}" alt="{{ $fixture->homeTeam->name }}" 
                                             class="team-logo mb-1">
                                    @endif
                                    <h6 class="mb-0 small">{{ $fixture->homeTeam->name }}</h6>
                                </div>
                                
                                <div class="col-4">
                                    @if($fixture->is_started)
                                        <div class="score-display">
                                            {{ $fixture->home_goals ?? 0 }} - {{ $fixture->away_goals ?? 0 }}
                                        </div>
                                        @if($fixture->is_live)
                                            <small class="text-danger">{{ $fixture->elapsed }}'</small>
                                        @endif
                                    @else
                                        <div class="text-muted">VS</div>
                                    @endif
                                </div>
                                
                                <div class="col-4">
                                    @if($fixture->awayTeam->logo)
                                        <img src="{{ $fixture->awayTeam->logo }}" alt="{{ $fixture->awayTeam->name }}" 
                                             class="team-logo mb-1">
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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                        <h3>{{ $matchDate->format('d.m.Y') }} tarihinde maç yok</h3>
                        <p class="text-muted mb-4">Bu tarihte programlanan herhangi bir maç bulunmuyor.</p>
                        <div class="suggestion-dates">
                            <p class="mb-3"><strong>Alternatif tarihler:</strong></p>
                            <a href="{{ route('match-tracking.date', now()->format('Y-m-d')) }}" 
                               class="btn btn-primary me-2">
                                Bugünkü Maçlar
                            </a>
                            <a href="{{ route('match-tracking.date', now()->addDay()->format('Y-m-d')) }}" 
                               class="btn btn-outline-primary">
                                Yarınki Maçlar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .date-navigation {
        display: flex;
        gap: 0.5rem;
    }

    .quick-dates {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .time-group {
        margin-bottom: 1.5rem;
    }

    .match-item {
        border-bottom: 1px solid #eee;
        transition: all 0.2s;
        position: relative;
    }

    .match-item:hover {
        background-color: var(--light-color);
    }

    .match-item.live {
        background: linear-gradient(90deg, rgba(239, 68, 68, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
        border-left: 4px solid var(--danger-color);
        animation: livePulse 3s infinite;
    }

    @keyframes livePulse {
        0%, 100% { box-shadow: 0 0 5px rgba(239, 68, 68, 0.3); }
        50% { box-shadow: 0 0 20px rgba(239, 68, 68, 0.6); }
    }

    .time-display {
        font-size: 1rem;
        position: relative;
    }

    .live-dot {
        width: 6px;
        height: 6px;
        background: var(--danger-color);
        border-radius: 50%;
        margin: 2px auto;
        animation: pulse 2s infinite;
    }

    .team-info {
        display: flex;
        align-items: center;
    }

    .team-info.home-team {
        justify-content: flex-start;
    }

    .team-info.away-team {
        justify-content: flex-end;
    }

    .team-logo-small {
        width: 25px;
        height: 25px;
        object-fit: contain;
    }

    .league-logo-small {
        width: 20px;
        height: 20px;
        object-fit: contain;
    }

    .score-display {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--dark-color);
    }

    .vs-text {
        color: var(--muted-color);
        font-weight: 500;
    }

    .match-result {
        min-height: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .additional-info {
        border-top: 1px solid #f0f0f0;
        margin-top: 0.5rem;
    }

    .league-name {
        display: block;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .match-grid-item .team-logo {
        width: 30px;
        height: 30px;
        object-fit: contain;
    }

    .suggestion-dates .btn {
        margin: 0.25rem;
    }

    @media (max-width: 768px) {
        .date-navigation {
            flex-direction: column;
        }
        
        .date-navigation .btn {
            margin-bottom: 0.5rem;
        }
        
        .match-item .row {
            margin-bottom: 0.5rem;
        }
        
        .team-info {
            justify-content: center !important;
            margin-bottom: 0.25rem;
        }
        
        .quick-dates {
            flex-direction: column;
            align-items: center;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let allLeagues = new Set();
    let currentView = 'list';

    // Sayfa yüklendiğinde
    document.addEventListener('DOMContentLoaded', function() {
        initializePage();
        loadStatistics();
        populateLeagueFilter();
        startLiveUpdates();
    });

    // Sayfayı başlat
    function initializePage() {
        calculateStatistics();
        updateTimeGroupStats();
    }

    // İstatistikleri hesapla
    function calculateStatistics() {
        const matches = document.querySelectorAll('.match-item, .match-grid-item');
        let liveCount = 0;
        let finishedCount = 0;
        let upcomingCount = 0;

        matches.forEach(function(match) {
            if (match.classList.contains('live') || match.dataset.status === 'live') {
                liveCount++;
            } else if (match.dataset.status === 'FT') {
                finishedCount++;
            } else {
                upcomingCount++;
            }
        });

        document.getElementById('live-matches').textContent = liveCount;
        document.getElementById('finished-matches').textContent = finishedCount;
        document.getElementById('upcoming-matches').textContent = upcomingCount;
    }

    // Zaman grubu istatistiklerini güncelle
    function updateTimeGroupStats() {
        const timeGroups = document.querySelectorAll('.time-group');
        
        timeGroups.forEach(function(group) {
            const timeSlot = group.dataset.time;
            const matches = group.querySelectorAll('.match-item');
            let liveCount = 0;
            let finishedCount = 0;
            
            matches.forEach(function(match) {
                if (match.classList.contains('live')) {
                    liveCount++;
                } else if (match.dataset.status === 'FT') {
                    finishedCount++;
                }
            });
            
            const liveCountEl = document.querySelector(`.live-count-${timeSlot.replace(':', '\\3A ')}`);
            const finishedCountEl = document.querySelector(`.finished-count-${timeSlot.replace(':', '\\3A ')}`);
            
            if (liveCountEl) liveCountEl.textContent = liveCount;
            if (finishedCountEl) finishedCountEl.textContent = finishedCount;
        });
    }

    // Lig filtresini doldur
    function populateLeagueFilter() {
        const matches = document.querySelectorAll('.match-item, .match-grid-item');
        const select = document.getElementById('league-filter');
        
        matches.forEach(function(match) {
            const league = match.dataset.league;
            if (league && league !== 'Unknown') {
                allLeagues.add(league);
            }
        });
        
        // Ligleri alfabetik sırala ve select'e ekle
        const sortedLeagues = Array.from(allLeagues).sort();
        sortedLeagues.forEach(function(league) {
            const option = document.createElement('option');
            option.value = league;
            option.textContent = league;
            select.appendChild(option);
        });
    }

    // Maçları filtrele
    function filterMatches() {
        const leagueFilter = document.getElementById('league-filter').value;
        const statusFilter = document.getElementById('status-filter').value;
        const teamSearch = document.getElementById('team-search').value.toLowerCase();

        const matches = document.querySelectorAll('.match-item, .match-grid-item');
        let visibleCount = 0;
        
        matches.forEach(function(match) {
            let show = true;

            // Lig filtresi
            if (leagueFilter && match.dataset.league !== leagueFilter) {
                show = false;
            }

            // Durum filtresi
            if (statusFilter && show) {
                const status = match.dataset.status;
                const isLive = match.classList.contains('live');
                
                if (statusFilter === 'live' && !isLive) show = false;
                if (statusFilter === 'finished' && status !== 'FT') show = false;
                if (statusFilter === 'upcoming' && (isLive || status === 'FT')) show = false;
                if (statusFilter === 'postponed' && status !== 'PST') show = false;
            }

            // Takım arama
            if (teamSearch && show) {
                const homeTeam = match.dataset.homeTeam.toLowerCase();
                const awayTeam = match.dataset.awayTeam.toLowerCase();
                if (!homeTeam.includes(teamSearch) && !awayTeam.includes(teamSearch)) {
                    show = false;
                }
            }

            match.style.display = show ? 'block' : 'none';
            if (show) visibleCount++;
        });

        // Zaman gruplarını gizle/göster
        updateTimeGroupVisibility();
        
        // Sonuç sayısını göster (isteğe bağlı)
        console.log(`${visibleCount} maç gösteriliyor`);
    }

    // Zaman gruplarının görünürlüğünü güncelle
    function updateTimeGroupVisibility() {
        const timeGroups = document.querySelectorAll('.time-group');
        
        timeGroups.forEach(function(group) {
            const visibleMatches = group.querySelectorAll('.match-item:not([style*="display: none"])');
            group.style.display = visibleMatches.length > 0 ? 'block' : 'none';
        });
    }

    // Filtreleri temizle
    function clearFilters() {
        document.getElementById('league-filter').value = '';
        document.getElementById('status-filter').value = '';
        document.getElementById('team-search').value = '';
        filterMatches();
    }

    // Görünümü değiştir (List/Grid)
    function toggleView() {
        const listView = document.getElementById('matches-container');
        const gridView = document.getElementById('grid-view');
        const icon = document.getElementById('view-toggle-icon');

        if (currentView === 'list') {
            // Grid view'a geç
            listView.style.display = 'none';
            gridView.style.display = 'block';
            icon.classList.remove('fa-th');
            icon.classList.add('fa-list');
            currentView = 'grid';
        } else {
            // List view'a geç
            listView.style.display = 'block';
            gridView.style.display = 'none';
            icon.classList.remove('fa-list');
            icon.classList.add('fa-th');
            currentView = 'list';
        }
        
        // Filtreleri yeniden uygula
        filterMatches();
    }

    // Tarihe git
    function goToDate(date) {
        if (date) {
            window.location.href = `/match-tracking/date/${date}`;
        }
    }

    // Canlı güncellemeleri başlat
    function startLiveUpdates() {
        // Her 30 saniyede bir canlı maçları güncelle
        setInterval(function() {
            updateLiveMatches();
        }, 30000);
    }

    // Canlı maçları güncelle
    function updateLiveMatches() {
        const date = '{{ $matchDate->format("Y-m-d") }}';
        
        fetch(`/api/football/date/${date}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    updateMatchesInDOM(data.data);
                }
            })
            .catch(error => {
                console.error('Live update error:', error);
            });
    }

    // DOM'daki maçları güncelle
    function updateMatchesInDOM(fixtures) {
        fixtures.forEach(function(fixture) {
            const matchElement = document.querySelector(`[data-fixture-id="${fixture.id}"]`);
            if (matchElement) {
                // Skoru güncelle
                const scoreDisplay = matchElement.querySelector('.score-display strong, .score-display');
                if (scoreDisplay) {
                    scoreDisplay.textContent = `${fixture.home_goals || 0} - ${fixture.away_goals || 0}`;
                }
                
                // Canlı durumu güncelle
                if (fixture.is_live && !matchElement.classList.contains('live')) {
                    matchElement.classList.add('live');
                } else if (!fixture.is_live && matchElement.classList.contains('live')) {
                    matchElement.classList.remove('live');
                }
                
                // Dakikayı güncelle
                const elapsedTime = matchElement.querySelector('small.text-danger, small.text-success');
                if (elapsedTime && fixture.is_live) {
                    elapsedTime.textContent = `${fixture.elapsed}'`;
                }
            }
        });
        
        // İstatistikleri yeniden hesapla
        calculateStatistics();
        updateTimeGroupStats();
    }

    // WebSocket bağlantısı (Echo)
    if (window.Echo) {
        const dateChannel = '{{ $matchDate->format("Y-m-d") }}';
        
        window.Echo.channel(`date.${dateChannel}`)
            .listen('.match.updated', (e) => {
                console.log('Match updated:', e);
                updateMatchesInDOM([e.fixture]);
            });
    }

    // Klavye kısayolları
    document.addEventListener('keydown', function(e) {
        // Sol ok tuşu: Önceki gün
        if (e.key === 'ArrowLeft' && e.ctrlKey) {
            e.preventDefault();
            const prevDate = '{{ $matchDate->copy()->subDay()->format("Y-m-d") }}';
            goToDate(prevDate);
        }
        
        // Sağ ok tuşu: Sonraki gün
        if (e.key === 'ArrowRight' && e.ctrlKey) {
            e.preventDefault();
            const nextDate = '{{ $matchDate->copy()->addDay()->format("Y-m-d") }}';
            goToDate(nextDate);
        }
        
        // T tuşu: Bugüne git
        if (e.key === 't' || e.key === 'T') {
            if (e.target.tagName !== 'INPUT') {
                e.preventDefault();
                const today = '{{ now()->format("Y-m-d") }}';
                goToDate(today);
            }
        }
        
        // V tuşu: Görünüm değiştir
        if (e.key === 'v' || e.key === 'V') {
            if (e.target.tagName !== 'INPUT') {
                e.preventDefault();
                toggleView();
            }
        }
    });
</script>
@endpush
@endsection 