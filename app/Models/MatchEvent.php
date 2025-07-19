<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'fixture_id',
        'team_id',
        'elapsed',
        'elapsed_plus',
        'type',
        'detail',
        'comments',
        'player_name',
        'player_id',
        'assist_name',
        'assist_id',
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
     * Gol olaylarını getir
     */
    public function scopeGoals($query)
    {
        return $query->where('type', 'Goal');
    }

    /**
     * Kart olaylarını getir
     */
    public function scopeCards($query)
    {
        return $query->where('type', 'Card');
    }

    /**
     * Oyuncu değişiklikleri
     */
    public function scopeSubstitutions($query)
    {
        return $query->where('type', 'subst');
    }

    /**
     * Dakika formatını getir
     */
    public function getFormattedElapsedAttribute()
    {
        $time = $this->elapsed;
        if ($this->elapsed_plus) {
            $time .= '+' . $this->elapsed_plus;
        }
        return $time . "'";
    }

    /**
     * Olay tipini Türkçe formatla
     */
    public function getTypeTurkishAttribute()
    {
        $typeMap = [
            'Goal' => 'Gol',
            'Card' => 'Kart',
            'subst' => 'Oyuncu Değişikliği',
            'Var' => 'VAR'
        ];

        return $typeMap[$this->type] ?? $this->type;
    }
}
