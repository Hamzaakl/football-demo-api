<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FootballApiService;
use App\Models\League;
use App\Models\Team;
use App\Models\Fixture;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FootballController extends Controller
{
    private $footballApiService;

    public function __construct(FootballApiService $footballApiService)
    {
        $this->footballApiService = $footballApiService;
    }

    /**
     * Canlı skorları getir
     */
    public function getLiveScores(): JsonResponse
    {
        try {
            $apiData = $this->footballApiService->getLiveMatches();
            $fixtures = Fixture::live()
                ->with(['homeTeam', 'awayTeam', 'league'])
                ->orderBy('fixture_date')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'live_fixtures' => $fixtures,
                    'api_data' => $apiData
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Canlı skorlar alınırken hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Maç detaylarını getir
     */
    public function getMatchDetails($fixtureId): JsonResponse
    {
        try {
            $fixture = Fixture::with([
                'homeTeam',
                'awayTeam', 
                'league',
                'matchEvents.team',
                'matchStatistics.team'
            ])->findOrFail($fixtureId);

            // API'den güncel bilgileri çek
            $apiData = $this->footballApiService->getMatchDetails($fixture->api_fixture_id);
            $statsData = $this->footballApiService->getMatchStatistics($fixture->api_fixture_id);

            return response()->json([
                'success' => true,
                'data' => [
                    'fixture' => $fixture,
                    'api_details' => $apiData,
                    'statistics' => $statsData
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Maç detayları alınırken hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bugünkü maçları getir
     */
    public function getTodaysMatches(): JsonResponse
    {
        try {
            $fixtures = Fixture::byDate(now()->format('Y-m-d'))
                ->with(['homeTeam', 'awayTeam', 'league'])
                ->orderBy('fixture_date')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $fixtures
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bugünkü maçlar alınırken hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Popüler ligleri getir
     */
    public function getPopularLeagues(): JsonResponse
    {
        try {
            $leagues = League::current()
                ->whereIn('country', ['Turkey', 'England', 'Spain', 'Italy', 'Germany', 'France'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $leagues
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ligler alınırken hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lig maçlarını getir
     */
    public function getLeagueMatches($leagueId): JsonResponse
    {
        try {
            $fixtures = Fixture::byLeague($leagueId)
                ->with(['homeTeam', 'awayTeam'])
                ->latest('fixture_date')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $fixtures
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lig maçları alınırken hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Takım maçlarını getir
     */
    public function getTeamMatches($teamId): JsonResponse
    {
        try {
            $fixtures = Fixture::byTeam($teamId)
                ->with(['homeTeam', 'awayTeam', 'league'])
                ->latest('fixture_date')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $fixtures
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Takım maçları alınırken hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API'den veri senkronizasyonu
     */
    public function syncData(): JsonResponse
    {
        try {
            // Canlı maçları çek ve güncelle
            $liveMatches = $this->footballApiService->getLiveMatches();
            
            if (isset($liveMatches['response'])) {
                foreach ($liveMatches['response'] as $matchData) {
                    $this->syncFixtureData($matchData);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Veriler başarıyla senkronize edildi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Veri senkronizasyonu sırasında hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Maç verisini senkronize et
     */
    private function syncFixtureData($apiData)
    {
        // Takımları kontrol et ve oluştur
        $homeTeam = $this->syncTeamData($apiData['teams']['home']);
        $awayTeam = $this->syncTeamData($apiData['teams']['away']);
        
        // Ligi kontrol et ve oluştur
        $league = $this->syncLeagueData($apiData['league']);

        // Maçı güncelle veya oluştur
        Fixture::updateOrCreate(
            ['api_fixture_id' => $apiData['fixture']['id']],
            [
                'league_id' => $league->id,
                'home_team_id' => $homeTeam->id,
                'away_team_id' => $awayTeam->id,
                'referee' => $apiData['fixture']['referee'] ?? null,
                'fixture_date' => $apiData['fixture']['date'],
                'venue_name' => $apiData['fixture']['venue']['name'] ?? null,
                'venue_city' => $apiData['fixture']['venue']['city'] ?? null,
                'status' => $apiData['fixture']['status']['short'],
                'status_long' => $apiData['fixture']['status']['long'],
                'elapsed' => $apiData['fixture']['status']['elapsed'],
                'home_goals' => $apiData['goals']['home'],
                'away_goals' => $apiData['goals']['away'],
                'score' => $apiData['score'],
                'season' => $apiData['league']['season'],
                'is_live' => in_array($apiData['fixture']['status']['short'], ['1H', '2H', 'HT', 'ET']),
                'last_updated' => now()
            ]
        );
    }

    /**
     * Takım verisini senkronize et
     */
    private function syncTeamData($teamData)
    {
        return Team::updateOrCreate(
            ['api_team_id' => $teamData['id']],
            [
                'name' => $teamData['name'],
                'logo' => $teamData['logo'],
                'country' => $teamData['country'] ?? null,
                'code' => $teamData['code'] ?? null,
            ]
        );
    }

    /**
     * Lig verisini senkronize et
     */
    private function syncLeagueData($leagueData)
    {
        return League::updateOrCreate(
            ['api_league_id' => $leagueData['id'], 'season' => $leagueData['season']],
            [
                'name' => $leagueData['name'],
                'logo' => $leagueData['logo'],
                'country' => $leagueData['country'] ?? null,
                'season' => $leagueData['season'],
                'current' => true,
            ]
        );
    }
}
