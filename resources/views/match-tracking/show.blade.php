@extends('layouts.app')

@section('title', $fixture->homeTeam->name . ' vs ' . $fixture->awayTeam->name)

@section('content')
<div class="container">
    <!-- Match Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card match-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-1">{{ $fixture->league->name ?? 'Bilinmeyen Lig' }}</h6>
                            <small class="text-muted">{{ $fixture->fixture_date->format('d.m.Y H:i') }}</small>
                        </div>
                        @if($fixture->is_live)
                            <span class="badge bg-danger live-indicator">CANLI - {{ $fixture->elapsed }}'</span>
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
                                     class="mb-2" style="width: 80px; height: 80px; object-fit: contain;">
                            @endif
                            <h4 class="mb-0">{{ $fixture->homeTeam->name }}</h4>
                        </div>
                        
                        <div class="col-4">
                            <div class="display-4 mb-2">
                                {{ $fixture->home_goals ?? 0 }} - {{ $fixture->away_goals ?? 0 }}
                            </div>
                            @if($fixture->referee)
                                <small class="text-muted">Hakem: {{ $fixture->referee }}</small>
                            @endif
                        </div>
                        
                        <div class="col-4">
                            @if($fixture->awayTeam->logo)
                                <img src="{{ $fixture->awayTeam->logo }}" alt="{{ $fixture->awayTeam->name }}" 
                                     class="mb-2" style="width: 80px; height: 80px; object-fit: contain;">
                            @endif
                            <h4 class="mb-0">{{ $fixture->awayTeam->name }}</h4>
                        </div>
                    </div>

                    @if($fixture->venue_name)
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $fixture->venue_name }}{{ $fixture->venue_city ? ', ' . $fixture->venue_city : '' }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Match Details Column -->
        <div class="col-lg-8">
            <!-- Match Events -->
            @if($fixture->matchEvents->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-clock me-2"></i>Maç Olayları</h5>
                </div>
                <div class="card-body">
                    @foreach($fixture->matchEvents as $event)
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3" style="width: 40px;">
                            <span class="badge bg-primary">{{ $event->formatted_elapsed }}</span>
                        </div>
                        <div class="me-3">
                            @if($event->type === 'Goal')
                                <i class="fas fa-futbol text-success"></i>
                            @elseif($event->type === 'Card')
                                <i class="fas fa-square text-warning"></i>
                            @elseif($event->type === 'subst')
                                <i class="fas fa-exchange-alt text-info"></i>
                            @else
                                <i class="fas fa-info-circle text-muted"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <strong>{{ $event->player_name }}</strong>
                            <span class="text-muted">({{ $event->team->name }})</span>
                            @if($event->detail)
                                <small class="d-block text-muted">{{ $event->detail }}</small>
                            @endif
                            @if($event->assist_name)
                                <small class="d-block text-info">Asist: {{ $event->assist_name }}</small>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Match Statistics -->
            @if($fixture->matchStatistics->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-chart-bar me-2"></i>Maç İstatistikleri</h5>
                </div>
                <div class="card-body">
                    @php
                        $homeStats = $fixture->matchStatistics->where('team_id', $fixture->home_team_id)->first();
                        $awayStats = $fixture->matchStatistics->where('team_id', $fixture->away_team_id)->first();
                    @endphp

                    @if($homeStats && $awayStats)
                        @foreach(['Şutlar' => 'total_shots', 'İsabetli Şutlar' => 'shots_on_goal', 'Top Hakimiyeti' => 'ball_possession', 'Korner' => 'corner_kicks', 'Faul' => 'fouls', 'Sarı Kart' => 'yellow_cards'] as $label => $field)
                        <div class="row align-items-center mb-2">
                            <div class="col-4 text-end">
                                <span class="fw-bold">{{ $homeStats->$field ?? 0 }}</span>
                            </div>
                            <div class="col-4 text-center">
                                <small class="text-muted">{{ $label }}</small>
                            </div>
                            <div class="col-4">
                                <span class="fw-bold">{{ $awayStats->$field ?? 0 }}</span>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Live Chat Column -->
        <div class="col-lg-4">
            <div class="card chat-container">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-comments me-2"></i>Canlı Sohbet
                    </h5>
                    <small class="text-muted" id="online-users">0 kişi online</small>
                </div>
                
                <div class="card-body p-0">
                    <div class="chat-messages p-3" id="chat-messages">
                        <!-- Mesajlar buraya yüklenecek -->
                        <div class="text-center text-muted">
                            <i class="fas fa-spinner fa-spin"></i> Mesajlar yükleniyor...
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <form id="chat-form">
                        <div class="input-group">
                            <input type="text" class="form-control" id="message-input" 
                                   placeholder="Mesajınızı yazın..." maxlength="500" required>
                            <input type="text" class="form-control" id="username-input" 
                                   placeholder="Adınız" maxlength="50" style="max-width: 100px;" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Chat Stats -->
            <div class="card mt-3">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-6">
                            <div class="stat-number" id="total-messages">0</div>
                            <small class="text-muted">Toplam Mesaj</small>
                        </div>
                        <div class="col-6">
                            <div class="stat-number" id="active-users">0</div>
                            <small class="text-muted">Aktif Kullanıcı</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .message-item {
        margin-bottom: 10px;
        padding: 8px 12px;
        border-radius: 12px;
        background: var(--light-color);
    }
    
    .message-item.system-message {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        color: white;
        text-align: center;
        font-weight: 500;
    }
    
    .message-author {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--primary-color);
    }
    
    .message-time {
        font-size: 0.75rem;
        color: var(--muted-color);
    }
    
    .message-content {
        margin-top: 4px;
    }
    
    #chat-messages {
        scrollbar-width: thin;
        scrollbar-color: #ccc transparent;
    }
    
    #chat-messages::-webkit-scrollbar {
        width: 6px;
    }
    
    #chat-messages::-webkit-scrollbar-track {
        background: transparent;
    }
    
    #chat-messages::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 3px;
    }
</style>
@endpush

@push('scripts')
<script>
    const fixtureId = {{ $fixture->id }};
    let userName = localStorage.getItem('chat_username') || '';
    let lastMessageId = 0;

    // Kullanıcı adını input'a set et
    if (userName) {
        document.getElementById('username-input').value = userName;
    }

    // Mesajları yükle
    function loadMessages() {
        fetch(`/api/chat/fixture/${fixtureId}/messages`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayMessages(data.data);
                    if (data.data.length > 0) {
                        lastMessageId = data.data[data.data.length - 1].id;
                    }
                }
            })
            .catch(error => {
                console.error('Messages load error:', error);
                document.getElementById('chat-messages').innerHTML = 
                    '<div class="text-center text-danger">Mesajlar yüklenirken hata oluştu.</div>';
            });
    }

    // Mesajları göster
    function displayMessages(messages) {
        const container = document.getElementById('chat-messages');
        container.innerHTML = '';
        
        if (messages.length === 0) {
            container.innerHTML = '<div class="text-center text-muted">Henüz mesaj yok. İlk mesajı siz gönderin!</div>';
            return;
        }
        
        messages.forEach(message => {
            addMessageToChat(message);
        });
        
        scrollToBottom();
    }

    // Chat'e mesaj ekle
    function addMessageToChat(message) {
        const container = document.getElementById('chat-messages');
        const messageEl = document.createElement('div');
        messageEl.className = `message-item ${message.is_system ? 'system-message' : ''}`;
        
        if (message.is_system) {
            messageEl.innerHTML = `
                <div class="message-content">${message.message}</div>
            `;
        } else {
            messageEl.innerHTML = `
                <div class="d-flex justify-content-between">
                    <span class="message-author">${message.user_name}</span>
                    <small class="message-time">${formatTime(message.created_at)}</small>
                </div>
                <div class="message-content">${message.message}</div>
            `;
        }
        
        container.appendChild(messageEl);
    }

    // Zamanı formatla
    function formatTime(timestamp) {
        return new Date(timestamp).toLocaleTimeString('tr-TR', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Chat'i aşağı kaydır
    function scrollToBottom() {
        const container = document.getElementById('chat-messages');
        container.scrollTop = container.scrollHeight;
    }

    // Mesaj gönder
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const messageInput = document.getElementById('message-input');
        const usernameInput = document.getElementById('username-input');
        const message = messageInput.value.trim();
        const username = usernameInput.value.trim();
        
        if (!message || !username) {
            alert('Lütfen mesaj ve kullanıcı adı girin!');
            return;
        }
        
        // Kullanıcı adını sakla
        localStorage.setItem('chat_username', username);
        
        // API'ye gönder
        fetch(`/api/chat/fixture/${fixtureId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken
            },
            body: JSON.stringify({
                message: message,
                user_name: username,
                user_avatar: null
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
                // Mesaj echo ile gelecek, manuel eklemeye gerek yok
            } else {
                alert('Mesaj gönderilemedi: ' + (data.message || 'Bilinmeyen hata'));
            }
        })
        .catch(error => {
            console.error('Send message error:', error);
            alert('Mesaj gönderilirken hata oluştu!');
        });
    });

    // İstatistikleri yükle
    function loadChatStats() {
        fetch(`/api/chat/fixture/${fixtureId}/stats`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('total-messages').textContent = data.data.total_messages;
                    document.getElementById('active-users').textContent = data.data.last_hour_messages;
                }
            })
            .catch(error => console.error('Stats load error:', error));
    }

    // Echo ile gerçek zamanlı mesajlar
    if (window.Echo) {
        window.Echo.channel(`fixture.${fixtureId}`)
            .listen('.message.sent', (e) => {
                addMessageToChat(e);
                scrollToBottom();
                
                // İstatistikleri güncelle
                const totalEl = document.getElementById('total-messages');
                totalEl.textContent = parseInt(totalEl.textContent) + 1;
            })
            .listen('.message.deleted', (e) => {
                // Mesaj silme işlemi (gelecekte eklenebilir)
                console.log('Message deleted:', e);
            });

        // Skor güncellemeleri
        window.Echo.channel(`fixture.${fixtureId}`)
            .listen('.score.updated', (e) => {
                // Skoru güncelle
                const scoreDisplay = document.querySelector('.display-4');
                if (scoreDisplay) {
                    scoreDisplay.textContent = `${e.home_goals || 0} - ${e.away_goals || 0}`;
                }
                
                // Durum güncelle
                const statusBadge = document.querySelector('.badge');
                if (statusBadge && e.is_live) {
                    statusBadge.textContent = `CANLI - ${e.elapsed}'`;
                    statusBadge.className = 'badge bg-danger live-indicator';
                }
            });
    }

    // Sayfa yüklendiğinde
    document.addEventListener('DOMContentLoaded', function() {
        loadMessages();
        loadChatStats();
        
        // Periyodik güncellemeler
        setInterval(loadChatStats, 60000); // Her dakika istatistikleri güncelle
    });
</script>
@endpush
@endsection 