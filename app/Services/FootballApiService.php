<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class FootballApiService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.rapidapi.football_key');
        $this->baseUrl = 'https://api-football-v1.p.rapidapi.com/v3';
    }

    /**
     * API çağrısı için ortak headers
     */
    private function getHeaders()
    {
        return [
            'X-RapidAPI-Key' => $this->apiKey,
            'X-RapidAPI-Host' => 'api-football-v1.p.rapidapi.com'
        ];
    }

    /**
     * Canlı maçları getir
     */
    public function getLiveMatches()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/fixtures", [
                    'live' => 'all'
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('API çağrısı başarısız: ' . $response->status());
        } catch (Exception $e) {
            logger('Football API Error: ' . $e->getMessage());
            return ['response' => []];
        }
    }

    /**
     * Belirli bir maçın detaylarını getir
     */
    public function getMatchDetails($fixtureId)
    {
        try {
            $cacheKey = "match_details_{$fixtureId}";
            
            return Cache::remember($cacheKey, 60, function () use ($fixtureId) {
                $response = Http::withHeaders($this->getHeaders())
                    ->get("{$this->baseUrl}/fixtures", [
                        'id' => $fixtureId
                    ]);

                if ($response->successful()) {
                    return $response->json();
                }

                throw new Exception('Maç detayları alınamadı');
            });
        } catch (Exception $e) {
            logger('Match Details Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Maç istatistiklerini getir
     */
    public function getMatchStatistics($fixtureId)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/fixtures/statistics", [
                    'fixture' => $fixtureId
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (Exception $e) {
            logger('Match Statistics Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Lig maçlarını getir
     */
    public function getLeagueMatches($leagueId, $season = null)
    {
        try {
            $params = ['league' => $leagueId];
            
            if ($season) {
                $params['season'] = $season;
            } else {
                $params['season'] = date('Y');
            }

            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/fixtures", $params);

            if ($response->successful()) {
                return $response->json();
            }

            return ['response' => []];
        } catch (Exception $e) {
            logger('League Matches Error: ' . $e->getMessage());
            return ['response' => []];
        }
    }

    /**
     * Takım bilgilerini getir
     */
    public function getTeamInfo($teamId)
    {
        try {
            $cacheKey = "team_info_{$teamId}";
            
            return Cache::remember($cacheKey, 3600, function () use ($teamId) {
                $response = Http::withHeaders($this->getHeaders())
                    ->get("{$this->baseUrl}/teams", [
                        'id' => $teamId
                    ]);

                if ($response->successful()) {
                    return $response->json();
                }

                return null;
            });
        } catch (Exception $e) {
            logger('Team Info Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Ligleri getir
     */
    public function getLeagues($country = null)
    {
        try {
            $params = [];
            if ($country) {
                $params['country'] = $country;
            }

            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/leagues", $params);

            if ($response->successful()) {
                return $response->json();
            }

            return ['response' => []];
        } catch (Exception $e) {
            logger('Leagues Error: ' . $e->getMessage());
            return ['response' => []];
        }
    }
} 