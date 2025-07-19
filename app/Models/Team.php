<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_team_id',
        'name',
        'code',
        'country',
        'founded',
        'national',
        'logo',
        'venue',
    ];

    protected $casts = [
        'national' => 'boolean',
        'venue' => 'array',
    ];

    /**
     * Takımın ev sahibi olduğu maçlar
     */
    public function homeFixtures()
    {
        return $this->hasMany(Fixture::class, 'home_team_id');
    }

    /**
     * Takımın deplasman olduğu maçlar
     */
    public function awayFixtures()
    {
        return $this->hasMany(Fixture::class, 'away_team_id');
    }

    /**
     * Takımın tüm maçları
     */
    public function fixtures()
    {
        return Fixture::where('home_team_id', $this->id)
                     ->orWhere('away_team_id', $this->id);
    }

    /**
     * Takımın maç olayları
     */
    public function matchEvents()
    {
        return $this->hasMany(MatchEvent::class);
    }

    /**
     * Takımın maç istatistikleri
     */
    public function matchStatistics()
    {
        return $this->hasMany(MatchStatistic::class);
    }

    /**
     * Milli takımları getir
     */
    public function scopeNational($query)
    {
        return $query->where('national', true);
    }

    /**
     * Kulüp takımlarını getir
     */
    public function scopeClub($query)
    {
        return $query->where('national', false);
    }

    /**
     * Belirli ülkenin takımlarını getir
     */
    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }
}
