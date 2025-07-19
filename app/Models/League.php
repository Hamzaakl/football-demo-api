<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_league_id',
        'name',
        'type',
        'logo',
        'country',
        'country_code',
        'season',
        'current',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'current' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Ligin maçları
     */
    public function fixtures()
    {
        return $this->hasMany(Fixture::class);
    }

    /**
     * Aktif ligleri getir
     */
    public function scopeCurrent($query)
    {
        return $query->where('current', true);
    }

    /**
     * Belirli ülkenin liglerini getir
     */
    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Belirli sezonun liglerini getir
     */
    public function scopeBySeason($query, $season)
    {
        return $query->where('season', $season);
    }
}
