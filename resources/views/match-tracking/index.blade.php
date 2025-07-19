@extends('layouts.app')

@section('title', 'Canlı Futbol Takip')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-futbol me-2"></i>Canlı Futbol Takip</h1>
                <button class="btn btn-primary" onclick="refreshData()">
                    <i class="fas fa-sync-alt me-1"></i>Yenile
                </button>
            </div>
        </div>
    </div>

    <!-- Live Matches Section -->
    @if($liveMatches->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center mb-3">
                <h2 class="h4 mb-0">
                    <span class="badge live-indicator text-white me-2">CANLI</span>
                    Devam Eden Maçlar
                </h2>
            </div>
            <div class="row">
                @foreach($liveMatches as $match)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card match-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">{{ $match->league->name ?? 'Bilinmeyen Lig' }}</small>
                                <span class="badge bg-danger live-indicator">{{ $match->status_turkish }}</span>
                            </div>
                            
                            <div class="row align-items-center text-center">
                                <div class="col-4">
                                    @if($match->homeTeam->logo)
                                        <img src="{{ $match->homeTeam->logo }}" alt="{{ $match->homeTeam->name }}" class="team-logo mb-2">
                                    @endif
                                    <h6 class="mb-0">{{ $match->homeTeam->name }}</h6>
                                </div>
                                
                                <div class="col-4">
                                    <div class="score-display">
                                        {{ $match->home_goals ?? 0 }} - {{ $match->away_goals ?? 0 }}
                                    </div>
                                    @if($match->elapsed)
                                        <small class="text-muted">{{ $match->elapsed }}'</small>
                                    @endif
                                </div>
                                
                                <div class="col-4">
                                    @if($match->awayTeam->logo)
                                        <img src="{{ $match->awayTeam->logo }}" alt="{{ $match->awayTeam->name }}" class="team-logo mb-2">
                                    @endif
                                    <h6 class="mb-0">{{ $match->awayTeam->name }}</h6>
                                </div>
                            </div>
                            
                            <div class="text-center mt-3">
                                <a href="{{ route('match-tracking.show', $match->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye me-1"></i>Detaylar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Today's Matches Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="h4 mb-3">
                <i class="fas fa-calendar-day me-2"></i>Bugünkü Maçlar
            </h2>
            
            @if($todayMatches->count() > 0)
                <div class="row">
                    @foreach($todayMatches as $match)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card match-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">{{ $match->league->name ?? 'Bilinmeyen Lig' }}</small>
                                    @if($match->is_live)
                                        <span class="badge bg-danger">CANLI</span>
                                    @elseif($match->is_finished)
                                        <span class="badge bg-success">BİTTİ</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $match->status_turkish }}</span>
                                    @endif
                                </div>
                                
                                <div class="row align-items-center text-center">
                                    <div class="col-4">
                                        @if($match->homeTeam->logo)
                                            <img src="{{ $match->homeTeam->logo }}" alt="{{ $match->homeTeam->name }}" class="team-logo mb-2">
                                        @endif
                                        <h6 class="mb-0">{{ $match->homeTeam->name }}</h6>
                                    </div>
                                    
                                    <div class="col-4">
                                        @if($match->is_started)
                                            <div class="score-display">
                                                {{ $match->home_goals ?? 0 }} - {{ $match->away_goals ?? 0 }}
                                            </div>
                                        @else
                                            <div class="text-muted">
                                                {{ $match->fixture_date->format('H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="col-4">
                                        @if($match->awayTeam->logo)
                                            <img src="{{ $match->awayTeam->logo }}" alt="{{ $match->awayTeam->name }}" class="team-logo mb-2">
                                        @endif
                                        <h6 class="mb-0">{{ $match->awayTeam->name }}</h6>
                                    </div>
                                </div>
                                
                                <div class="text-center mt-3">
                                    <a href="{{ route('match-tracking.show', $match->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i>Detaylar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Bugün herhangi bir maç bulunmuyor.
                </div>
            @endif
        </div>
    </div>

    <!-- Popular Leagues Section -->
    @if($popularLeagues->count() > 0)
    <div class="row">
        <div class="col-12">
            <h2 class="h4 mb-3">
                <i class="fas fa-trophy me-2"></i>Popüler Ligler
            </h2>
            
            <div class="row">
                @foreach($popularLeagues as $league)
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            @if($league->logo)
                                <img src="{{ $league->logo }}" alt="{{ $league->name }}" class="mb-3" style="width: 60px; height: 60px; object-fit: contain;">
                            @endif
                            <h5 class="card-title">{{ $league->name }}</h5>
                            <p class="card-text text-muted">{{ $league->country }}</p>
                            <a href="{{ route('match-tracking.league', $league->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Maçları Gör
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Sayfa yenileme fonksiyonu
    function refreshData() {
        window.location.reload();
    }

    // Canlı skorlar için otomatik yenileme
    setInterval(function() {
        // API'den canlı skorları güncelle
        fetch('/api/football/live-scores')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.live_fixtures) {
                    updateLiveScores(data.data.live_fixtures);
                }
            })
            .catch(error => {
                console.log('Live score update error:', error);
            });
    }, 30000); // 30 saniyede bir güncelle

    function updateLiveScores(fixtures) {
        // Canlı skorları güncelle (basit implementasyon)
        fixtures.forEach(function(fixture) {
            const scoreElement = document.querySelector(`[data-fixture-id="${fixture.id}"] .score-display`);
            if (scoreElement) {
                scoreElement.textContent = `${fixture.home_goals || 0} - ${fixture.away_goals || 0}`;
            }
        });
    }

    // Echo ile gerçek zamanlı güncellemeler
    if (window.Echo) {
        window.Echo.channel('live-scores')
            .listen('.score.updated', (e) => {
                console.log('Score updated:', e);
                // Skorları güncelle
                const matchCard = document.querySelector(`[data-fixture-id="${e.fixture_id}"]`);
                if (matchCard) {
                    const scoreDisplay = matchCard.querySelector('.score-display');
                    if (scoreDisplay) {
                        scoreDisplay.textContent = `${e.home_goals || 0} - ${e.away_goals || 0}`;
                    }
                }
            });
    }
</script>
@endpush
@endsection 