<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Fixture extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_fixture_id',
        'league_id',
        'home_team_id',
        'away_team_id',
        'referee',
        'timezone',
        'fixture_date',
        'venue_name',
        'venue_city',
        'status',
        'status_long',
        'elapsed',
        'home_goals',
        'away_goals',
        'score',
        'round',
        'season',
        'is_live',
        'last_updated',
    ];

    protected $casts = [
        'fixture_date' => 'datetime',
        'score' => 'array',
        'is_live' => 'boolean',
        'last_updated' => 'datetime',
    ];

    /**
     * Maçın ligi
     */
    public function league()
    {
        return $this->belongsTo(League::class);
    }

    /**
     * Ev sahibi takım
     */
    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    /**
     * Deplasman takımı
     */
    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    /**
     * Maç olayları
     */
    public function matchEvents()
    {
        return $this->hasMany(MatchEvent::class);
    }

    /**
     * Maç istatistikleri
     */
    public function matchStatistics()
    {
        return $this->hasMany(MatchStatistic::class);
    }

    /**
     * Sohbet mesajları
     */
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * Canlı maçları getir
     */
    public function scopeLive($query)
    {
        return $query->where('is_live', true);
    }

    /**
     * Tamamlanan maçları getir
     */
    public function scopeFinished($query)
    {
        return $query->where('status', 'FT');
    }

    /**
     * Belirli tarihteki maçları getir
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('fixture_date', $date);
    }

    /**
     * Belirli takımın maçlarını getir
     */
    public function scopeByTeam($query, $teamId)
    {
        return $query->where('home_team_id', $teamId)
                     ->orWhere('away_team_id', $teamId);
    }

    /**
     * Belirli ligin maçlarını getir
     */
    public function scopeByLeague($query, $leagueId)
    {
        return $query->where('league_id', $leagueId);
    }

    /**
     * Maç sonucunu formatla
     */
    public function getFormattedScoreAttribute()
    {
        if (is_null($this->home_goals) || is_null($this->away_goals)) {
            return '-';
        }
        
        return $this->home_goals . ' - ' . $this->away_goals;
    }

    /**
     * Maçın başlayıp başlamadığını kontrol et
     */
    public function getIsStartedAttribute()
    {
        return !in_array($this->status, ['NS', 'PST', 'CANC']);
    }

    /**
     * Maçın bitip bitmediğini kontrol et
     */
    public function getIsFinishedAttribute()
    {
        return in_array($this->status, ['FT', 'AET', 'PEN']);
    }

    /**
     * Maç durumunu Türkçe formatla
     */
    public function getStatusTurkishAttribute()
    {
        $statusMap = [
            'NS' => 'Başlamadı',
            '1H' => '1. Yarı',
            'HT' => 'Devre Arası',
            '2H' => '2. Yarı',
            'ET' => 'Uzatma',
            'FT' => 'Bitti',
            'AET' => 'Uzatmada Bitti',
            'PEN' => 'Penaltılarla Bitti',
            'PST' => 'Ertelendi',
            'CANC' => 'İptal',
            'SUSP' => 'Askıya Alındı'
        ];

        return $statusMap[$this->status] ?? $this->status;
    }
}
