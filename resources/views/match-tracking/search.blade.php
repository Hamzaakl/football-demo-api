@extends('layouts.app')

@section('title', isset($query) ? '"' . $query . '" Arama Sonuçları' : 'Arama')

@section('content')
<div class="container">
    <!-- Search Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="mb-3">
                        <i class="fas fa-search me-2"></i>
                        @if(isset($query))
                            "{{ $query }}" için Arama Sonuçları
                        @else
                            Futbol Arama
                        @endif
                    </h1>
                    
                    <!-- Advanced Search Form -->
                    <form method="GET" action="{{ route('match-tracking.search') }}" id="search-form">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Arama Terimi</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="q" value="{{ $query ?? '' }}" 
                                           placeholder="Takım, lig veya oyuncu adı..." id="main-search">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Ara
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Kategori</label>
                                <select class="form-select" name="category" id="category-filter">
                                    <option value="">Hepsi</option>
                                    <option value="teams">Takımlar</option>
                                    <option value="leagues">Ligler</option>
                                    <option value="matches">Maçlar</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Ülke</label>
                                <select class="form-select" name="country" id="country-filter">
                                    <option value="">Tüm Ülkeler</option>
                                    <option value="Turkey">Türkiye</option>
                                    <option value="England">İngiltere</option>
                                    <option value="Spain">İspanya</option>
                                    <option value="Italy">İtalya</option>
                                    <option value="Germany">Almanya</option>
                                    <option value="France">Fransa</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Quick Search Buttons -->
                        <div class="quick-searches mb-3">
                            <h6>Popüler Aramalar:</h6>
                            <button type="button" class="btn btn-outline-primary btn-sm me-2 mb-1" onclick="quickSearch('Galatasaray')">Galatasaray</button>
                            <button type="button" class="btn btn-outline-primary btn-sm me-2 mb-1" onclick="quickSearch('Fenerbahçe')">Fenerbahçe</button>
                            <button type="button" class="btn btn-outline-primary btn-sm me-2 mb-1" onclick="quickSearch('Beşiktaş')">Beşiktaş</button>
                            <button type="button" class="btn btn-outline-primary btn-sm me-2 mb-1" onclick="quickSearch('Barcelona')">Barcelona</button>
                            <button type="button" class="btn btn-outline-primary btn-sm me-2 mb-1" onclick="quickSearch('Real Madrid')">Real Madrid</button>
                            <button type="button" class="btn btn-outline-primary btn-sm mb-1" onclick="quickSearch('Premier League')">Premier League</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($query))
    <!-- Search Results -->
    <div class="row">
        <!-- Results Summary -->
        <div class="col-12 mb-4">
            <div class="search-summary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @php
                            $totalResults = ($teams->count() ?? 0) + ($leagues->count() ?? 0) + ($fixtures->count() ?? 0);
                        @endphp
                        <h5>
                            <span class="badge bg-primary">{{ $totalResults }}</span> sonuç bulundu
                        </h5>
                        <small class="text-muted">
                            {{ $teams->count() ?? 0 }} takım, 
                            {{ $leagues->count() ?? 0 }} lig, 
                            {{ $fixtures->count() ?? 0 }} maç
                        </small>
                    </div>
                    <div class="result-filters">
                        <button class="btn btn-sm btn-outline-secondary me-1 filter-btn active" 
                                data-filter="all">Hepsi</button>
                        <button class="btn btn-sm btn-outline-secondary me-1 filter-btn" 
                                data-filter="teams">Takımlar ({{ $teams->count() ?? 0 }})</button>
                        <button class="btn btn-sm btn-outline-secondary me-1 filter-btn" 
                                data-filter="leagues">Ligler ({{ $leagues->count() ?? 0 }})</button>
                        <button class="btn btn-sm btn-outline-secondary filter-btn" 
                                data-filter="matches">Maçlar ({{ $fixtures->count() ?? 0 }})</button>
                    </div>
                </div>
            </div>
        </div>

        @if($totalResults == 0)
            <!-- No Results -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-search-minus fa-4x text-muted mb-4"></i>
                        <h3>Sonuç Bulunamadı</h3>
                        <p class="text-muted mb-4">
                            "<strong>{{ $query }}</strong>" için herhangi bir sonuç bulunamadı.
                        </p>
                        <div class="suggestions">
                            <h6>Öneriler:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Yazım hatası olup olmadığını kontrol edin</li>
                                <li><i class="fas fa-check text-success me-2"></i>Daha genel arama terimleri kullanın</li>
                                <li><i class="fas fa-check text-success me-2"></i>Takım veya lig adının tam halini yazın</li>
                                <li><i class="fas fa-check text-success me-2"></i>İngilizce isimlerini de deneyin</li>
                            </ul>
                        </div>
                        <div class="mt-4">
                            <button class="btn btn-primary me-2" onclick="document.getElementById('main-search').focus()">
                                <i class="fas fa-search me-1"></i>Tekrar Ara
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-primary">
                                <i class="fas fa-home me-1"></i>Ana Sayfaya Dön
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Teams Results -->
            @if(isset($teams) && $teams->count() > 0)
            <div class="col-12 mb-4 result-section" data-section="teams">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-shield-alt me-2"></i>Takımlar ({{ $teams->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="teams-results">
                            @foreach($teams as $team)
                            <div class="result-item team-item">
                                <div class="row align-items-center py-3">
                                    <div class="col-md-2 text-center">
                                        @if($team->logo)
                                            <img src="{{ $team->logo }}" alt="{{ $team->name }}" 
                                                 class="team-logo">
                                        @else
                                            <div class="team-logo-placeholder">
                                                <i class="fas fa-shield-alt text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-1">{{ $team->name }}</h6>
                                        @if($team->code)
                                            <small class="text-primary d-block">{{ $team->code }}</small>
                                        @endif
                                        <div class="team-info">
                                            @if($team->country)
                                                <span class="badge bg-light text-dark me-1">
                                                    <i class="fas fa-flag me-1"></i>{{ $team->country }}
                                                </span>
                                            @endif
                                            @if($team->founded)
                                                <span class="badge bg-light text-dark me-1">{{ $team->founded }}</span>
                                            @endif
                                            @if($team->national)
                                                <span class="badge bg-primary">Milli Takım</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <a href="{{ route('match-tracking.team', $team->id) }}" 
                                           class="btn btn-outline-primary me-2">
                                            <i class="fas fa-eye me-1"></i>Profil
                                        </a>
                                        <button class="btn btn-outline-secondary" onclick="addToFavorites('team', {{ $team->id }})">
                                            <i class="fas fa-heart me-1"></i>Favoriler
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Leagues Results -->
            @if(isset($leagues) && $leagues->count() > 0)
            <div class="col-12 mb-4 result-section" data-section="leagues">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-trophy me-2"></i>Ligler ({{ $leagues->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="leagues-results">
                            @foreach($leagues as $league)
                            <div class="result-item league-item">
                                <div class="row align-items-center py-3">
                                    <div class="col-md-2 text-center">
                                        @if($league->logo)
                                            <img src="{{ $league->logo }}" alt="{{ $league->name }}" 
                                                 class="league-logo">
                                        @else
                                            <div class="league-logo-placeholder">
                                                <i class="fas fa-trophy text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-1">{{ $league->name }}</h6>
                                        <div class="league-info">
                                            @if($league->country)
                                                <span class="badge bg-light text-dark me-1">
                                                    <i class="fas fa-flag me-1"></i>{{ $league->country }}
                                                </span>
                                            @endif
                                            @if($league->season)
                                                <span class="badge bg-light text-dark me-1">{{ $league->season }}</span>
                                            @endif
                                            @if($league->type)
                                                <span class="badge bg-info">{{ $league->type }}</span>
                                            @endif
                                            @if($league->current)
                                                <span class="badge bg-success">Aktif</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <a href="{{ route('match-tracking.league', $league->id) }}" 
                                           class="btn btn-outline-primary me-2">
                                            <i class="fas fa-eye me-1"></i>Maçlar
                                        </a>
                                        <button class="btn btn-outline-secondary" onclick="addToFavorites('league', {{ $league->id }})">
                                            <i class="fas fa-heart me-1"></i>Takip Et
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Matches Results -->
            @if(isset($fixtures) && $fixtures->count() > 0)
            <div class="col-12 mb-4 result-section" data-section="matches">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-futbol me-2"></i>Maçlar ({{ $fixtures->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="matches-results">
                            @foreach($fixtures as $fixture)
                            <div class="result-item match-item {{ $fixture->is_live ? 'live' : '' }}">
                                <div class="row align-items-center py-3">
                                    <div class="col-md-2 text-center">
                                        <div class="match-date">
                                            <small class="text-muted d-block">{{ $fixture->fixture_date->format('d.m.Y') }}</small>
                                            <strong>{{ $fixture->fixture_date->format('H:i') }}</strong>
                                        </div>
                                        @if($fixture->is_live)
                                            <div class="live-indicator-small"></div>
                                        @endif
                                    </div>
                                    <div class="col-md-8">
                                        <div class="match-teams">
                                            <div class="row align-items-center">
                                                <div class="col-5">
                                                    <div class="team-info">
                                                        @if($fixture->homeTeam->logo)
                                                            <img src="{{ $fixture->homeTeam->logo }}" 
                                                                 alt="{{ $fixture->homeTeam->name }}" 
                                                                 class="team-logo-small me-2">
                                                        @endif
                                                        <span class="fw-bold">{{ $fixture->homeTeam->name }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-2 text-center">
                                                    @if($fixture->is_started)
                                                        <div class="score-display">
                                                            {{ $fixture->home_goals ?? 0 }} - {{ $fixture->away_goals ?? 0 }}
                                                        </div>
                                                        @if($fixture->is_live)
                                                            <small class="text-danger">{{ $fixture->elapsed }}'</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">VS</span>
                                                    @endif
                                                </div>
                                                <div class="col-5">
                                                    <div class="team-info justify-content-end">
                                                        <span class="fw-bold">{{ $fixture->awayTeam->name }}</span>
                                                        @if($fixture->awayTeam->logo)
                                                            <img src="{{ $fixture->awayTeam->logo }}" 
                                                                 alt="{{ $fixture->awayTeam->name }}" 
                                                                 class="team-logo-small ms-2">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="match-league mt-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-trophy me-1"></i>{{ $fixture->league->name ?? 'Bilinmeyen Lig' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <div class="match-status mb-2">
                                            @if($fixture->is_live)
                                                <span class="badge bg-danger">CANLI</span>
                                            @elseif($fixture->is_finished)
                                                <span class="badge bg-success">BİTTİ</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $fixture->status_turkish }}</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('match-tracking.show', $fixture->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye me-1"></i>Detay
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endif
    </div>

    <!-- Recent Searches -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Son Aramalar
                        <button class="btn btn-sm btn-outline-secondary ms-2" onclick="clearRecentSearches()">
                            <i class="fas fa-trash me-1"></i>Temizle
                        </button>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="recent-searches">
                        <p class="text-muted">Henüz arama yapmadınız.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- Search Suggestions (when no query) -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Arama İpuçları</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Takım adlarını tam olarak yazın</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Lig isimlerini İngilizce de deneyin</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Oyuncu isimlerini arayabilirsiniz</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Şehir isimlerini kullanabilirsiniz</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Kısaltmaları da deneyin (BJK, FB, GS)</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Popüler Aramalar</h5>
                </div>
                <div class="card-body">
                    <div class="popular-searches">
                        <button class="btn btn-outline-primary btn-sm me-2 mb-2" onclick="quickSearch('Champions League')">
                            Champions League
                        </button>
                        <button class="btn btn-outline-primary btn-sm me-2 mb-2" onclick="quickSearch('Premier League')">
                            Premier League
                        </button>
                        <button class="btn btn-outline-primary btn-sm me-2 mb-2" onclick="quickSearch('La Liga')">
                            La Liga
                        </button>
                        <button class="btn btn-outline-primary btn-sm me-2 mb-2" onclick="quickSearch('Serie A')">
                            Serie A
                        </button>
                        <button class="btn btn-outline-primary btn-sm me-2 mb-2" onclick="quickSearch('Bundesliga')">
                            Bundesliga
                        </button>
                        <button class="btn btn-outline-primary btn-sm me-2 mb-2" onclick="quickSearch('Süper Lig')">
                            Süper Lig
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    .team-logo, .league-logo {
        width: 50px;
        height: 50px;
        object-fit: contain;
    }

    .team-logo-placeholder, .league-logo-placeholder {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--light-color);
        border-radius: 8px;
        margin: 0 auto;
    }

    .team-logo-small {
        width: 25px;
        height: 25px;
        object-fit: contain;
    }

    .result-item {
        border-bottom: 1px solid #eee;
        transition: all 0.2s;
    }

    .result-item:hover {
        background-color: var(--light-color);
    }

    .result-item.live {
        background: linear-gradient(90deg, rgba(239, 68, 68, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
        border-left: 4px solid var(--danger-color);
    }

    .live-indicator-small {
        width: 8px;
        height: 8px;
        background: var(--danger-color);
        border-radius: 50%;
        margin: 4px auto;
        animation: pulse 2s infinite;
    }

    .filter-btn.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .team-info {
        display: flex;
        align-items: center;
    }

    .score-display {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--dark-color);
    }

    .quick-searches {
        border-top: 1px solid #eee;
        padding-top: 1rem;
    }

    .search-summary {
        background: var(--light-color);
        padding: 1rem;
        border-radius: 8px;
    }

    .popular-searches .btn {
        margin: 0.25rem;
    }

    .recent-searches-item {
        display: inline-block;
        margin: 0.25rem;
    }

    .match-teams .row {
        margin: 0;
    }

    .match-teams .col-5,
    .match-teams .col-2 {
        padding: 0 0.5rem;
    }

    @media (max-width: 768px) {
        .result-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .filter-btn {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        
        .team-info, .match-teams {
            flex-direction: column;
            text-align: center;
        }
        
        .match-teams .col-5, .match-teams .col-2 {
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let recentSearches = JSON.parse(localStorage.getItem('recent_searches') || '[]');

    // Sayfa yüklendiğinde
    document.addEventListener('DOMContentLoaded', function() {
        displayRecentSearches();
        initializeFilters();
        
        // Arama yapıldıysa kaydet
        @if(isset($query))
            saveSearch('{{ $query }}');
        @endif
    });

    // Hızlı arama
    function quickSearch(term) {
        document.getElementById('main-search').value = term;
        document.getElementById('search-form').submit();
    }

    // Arama filtreleri
    function initializeFilters() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Active sınıfını değiştir
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Sonuçları filtrele
                const filter = this.dataset.filter;
                filterResults(filter);
            });
        });
    }

    // Sonuçları filtrele
    function filterResults(filter) {
        const sections = document.querySelectorAll('.result-section');
        
        sections.forEach(section => {
            const sectionType = section.dataset.section;
            
            if (filter === 'all' || filter === sectionType) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });
    }

    // Favorilere ekle
    function addToFavorites(type, id) {
        const storageKey = `favorite_${type}s`;
        let favorites = JSON.parse(localStorage.getItem(storageKey) || '[]');
        
        if (!favorites.includes(id)) {
            favorites.push(id);
            localStorage.setItem(storageKey, JSON.stringify(favorites));
            showNotification(`${type === 'team' ? 'Takım' : 'Lig'} favorilere eklendi!`, 'success');
        } else {
            showNotification(`Bu ${type === 'team' ? 'takım' : 'lig'} zaten favorilerinizde!`, 'info');
        }
    }

    // Son aramaları kaydet
    function saveSearch(query) {
        if (query && query.trim()) {
            // Aynı arama varsa önce çıkar
            recentSearches = recentSearches.filter(search => search.term !== query);
            
            // Başa ekle
            recentSearches.unshift({
                term: query,
                date: new Date().toISOString(),
                timestamp: Date.now()
            });
            
            // En fazla 10 arama sakla
            recentSearches = recentSearches.slice(0, 10);
            
            localStorage.setItem('recent_searches', JSON.stringify(recentSearches));
            displayRecentSearches();
        }
    }

    // Son aramaları göster
    function displayRecentSearches() {
        const container = document.getElementById('recent-searches');
        
        if (recentSearches.length === 0) {
            container.innerHTML = '<p class="text-muted">Henüz arama yapmadınız.</p>';
            return;
        }
        
        let html = '';
        recentSearches.forEach((search, index) => {
            const date = new Date(search.date);
            const timeAgo = getTimeAgo(date);
            
            html += `
                <div class="recent-searches-item">
                    <button class="btn btn-outline-secondary btn-sm" onclick="quickSearch('${search.term}')">
                        <i class="fas fa-history me-1"></i>${search.term}
                        <small class="text-muted ms-1">(${timeAgo})</small>
                    </button>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }

    // Zaman farkını hesapla
    function getTimeAgo(date) {
        const now = new Date();
        const diff = now - date;
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        
        if (days > 0) return `${days} gün önce`;
        if (hours > 0) return `${hours} saat önce`;
        if (minutes > 0) return `${minutes} dk önce`;
        return 'Az önce';
    }

    // Son aramaları temizle
    function clearRecentSearches() {
        if (confirm('Son aramalar temizlensin mi?')) {
            recentSearches = [];
            localStorage.removeItem('recent_searches');
            displayRecentSearches();
            showNotification('Son aramalar temizlendi!', 'info');
        }
    }

    // Bildirim göster
    function showNotification(message, type = 'info') {
        // Basit alert - gerçek projede toast notification kullanılabilir
        alert(message);
    }

    // Klavye kısayolları
    document.addEventListener('keydown', function(e) {
        // Ctrl+K: Arama kutusuna odaklan
        if (e.ctrlKey && e.key === 'k') {
            e.preventDefault();
            document.getElementById('main-search').focus();
        }
        
        // Escape: Arama kutusunu temizle
        if (e.key === 'Escape' && e.target.id === 'main-search') {
            e.target.value = '';
        }
    });

    // Arama kutusunda Enter tuşu
    document.getElementById('main-search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('search-form').submit();
        }
    });

    // Otomatik tamamlama önerisi (basit implementasyon)
    let searchTimeout;
    document.getElementById('main-search').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const query = e.target.value;
        
        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                // Gerçek projede API'den öneri alınabilir
                console.log('Search suggestions for:', query);
            }, 300);
        }
    });

    // URL parametrelerini form'a yükle
    @if(request('category'))
        document.getElementById('category-filter').value = '{{ request("category") }}';
    @endif
    
    @if(request('country'))
        document.getElementById('country-filter').value = '{{ request("country") }}';
    @endif
</script>
@endpush
@endsection 