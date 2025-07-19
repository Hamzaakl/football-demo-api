<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\League;
use App\Models\Team;
use App\Services\FootballApiService;
use Illuminate\Http\Request;

class MatchTrackingController extends Controller
{
    private $footballApiService;

    public function __construct(FootballApiService $footballApiService)
    {
        $this->footballApiService = $footballApiService;
    }

    /**
     * Ana maç takip sayfası
     */
    public function index()
    {
        // Bugünkü maçlar
        $todayMatches = Fixture::byDate(now()->format('Y-m-d'))
            ->with(['homeTeam', 'awayTeam', 'league'])
            ->orderBy('fixture_date')
            ->get();

        // Canlı maçlar
        $liveMatches = Fixture::live()
            ->with(['homeTeam', 'awayTeam', 'league'])
            ->orderBy('fixture_date')
            ->get();

        // Popüler ligler
        $popularLeagues = League::current()
            ->whereIn('country', ['Turkey', 'England', 'Spain', 'Italy', 'Germany'])
            ->get();

        return view('match-tracking.index', compact(
            'todayMatches',
            'liveMatches', 
            'popularLeagues'
        ));
    }

    /**
     * Maç detay sayfası
     */
    public function show($id)
    {
        $fixture = Fixture::with([
            'homeTeam',
            'awayTeam',
            'league',
            'matchEvents' => function($query) {
                $query->orderBy('elapsed')->orderBy('created_at');
            },
            'matchEvents.team',
            'matchStatistics.team',
            'chatMessages' => function($query) {
                $query->latest()->limit(50);
            }
        ])->findOrFail($id);

        // API'den güncel bilgileri çek
        $apiDetails = null;
        $apiStats = null;
        
        try {
            $apiDetails = $this->footballApiService->getMatchDetails($fixture->api_fixture_id);
            $apiStats = $this->footballApiService->getMatchStatistics($fixture->api_fixture_id);
        } catch (\Exception $e) {
            logger('API Error: ' . $e->getMessage());
        }

        return view('match-tracking.show', compact(
            'fixture',
            'apiDetails',
            'apiStats'
        ));
    }

    /**
     * Lig sayfası
     */
    public function league($id)
    {
        $league = League::findOrFail($id);
        
        $fixtures = Fixture::byLeague($id)
            ->with(['homeTeam', 'awayTeam'])
            ->orderBy('fixture_date', 'desc')
            ->paginate(20);

        return view('match-tracking.league', compact('league', 'fixtures'));
    }

    /**
     * Takım sayfası
     */
    public function team($id)
    {
        $team = Team::findOrFail($id);
        
        $fixtures = Fixture::byTeam($id)
            ->with(['homeTeam', 'awayTeam', 'league'])
            ->orderBy('fixture_date', 'desc')
            ->paginate(15);

        return view('match-tracking.team', compact('team', 'fixtures'));
    }

    /**
     * Canlı skorlar sayfası
     */
    public function liveScores()
    {
        $liveMatches = Fixture::live()
            ->with(['homeTeam', 'awayTeam', 'league'])
            ->orderBy('fixture_date')
            ->get();

        return view('match-tracking.live', compact('liveMatches'));
    }

    /**
     * Belirli tarihteki maçlar
     */
    public function matchesByDate($date)
    {
        try {
            $matchDate = \Carbon\Carbon::parse($date);
        } catch (\Exception $e) {
            $matchDate = now();
        }

        $fixtures = Fixture::byDate($matchDate->format('Y-m-d'))
            ->with(['homeTeam', 'awayTeam', 'league'])
            ->orderBy('fixture_date')
            ->get();

        return view('match-tracking.date', compact('fixtures', 'matchDate'));
    }

    /**
     * Arama fonksiyonu
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return redirect()->route('match-tracking.index');
        }

        // Takım arama
        $teams = Team::where('name', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get();

        // Lig arama
        $leagues = League::where('name', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        // Maç arama (takım adlarına göre)
        $fixtures = Fixture::whereHas('homeTeam', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->orWhereHas('awayTeam', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->with(['homeTeam', 'awayTeam', 'league'])
            ->orderBy('fixture_date', 'desc')
            ->limit(15)
            ->get();

        return view('match-tracking.search', compact('teams', 'leagues', 'fixtures', 'query'));
    }
}
