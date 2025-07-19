<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'fixture_id',
        'team_id',
        'shots_on_goal',
        'shots_off_goal',
        'total_shots',
        'blocked_shots',
        'shots_inside_box',
        'shots_outside_box',
        'fouls',
        'corner_kicks',
        'offsides',
        'ball_possession',
        'yellow_cards',
        'red_cards',
        'goalkeeper_saves',
        'total_passes',
        'passes_accurate',
        'passes_percentage',
        'additional_stats',
    ];

    protected $casts = [
        'additional_stats' => 'array',
    ];

    /**
     * Maç
     */
    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }

    /**
     * Takım
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Top possession yüzdesini sayıya çevir
     */
    public function getBallPossessionPercentAttribute()
    {
        if (!$this->ball_possession) {
            return 0;
        }
        
        return (int) str_replace('%', '', $this->ball_possession);
    }

    /**
     * Pas isabetlilik oranını sayıya çevir
     */
    public function getPassesAccuracyPercentAttribute()
    {
        if (!$this->passes_percentage) {
            return 0;
        }
        
        return (int) str_replace('%', '', $this->passes_percentage);
    }

    /**
     * İstatistikleri formatlı olarak getir
     */
    public function getFormattedStatsAttribute()
    {
        return [
            'Şutlar' => $this->total_shots ?? 0,
            'İsabetli Şutlar' => $this->shots_on_goal ?? 0,
            'Korner' => $this->corner_kicks ?? 0,
            'Faul' => $this->fouls ?? 0,
            'Ofsayt' => $this->offsides ?? 0,
            'Top Hakimiyeti' => $this->ball_possession ?? '0%',
            'Sarı Kart' => $this->yellow_cards ?? 0,
            'Kırmızı Kart' => $this->red_cards ?? 0,
        ];
    }
}
