<?php

namespace App\Events;

use App\Models\Fixture;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScoreUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fixture;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Fixture $fixture)
    {
        $this->fixture = $fixture;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new Channel('fixture.' . $this->fixture->id),
            new Channel('live-scores')
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'fixture_id' => $this->fixture->id,
            'home_team' => $this->fixture->homeTeam->name,
            'away_team' => $this->fixture->awayTeam->name,
            'home_goals' => $this->fixture->home_goals,
            'away_goals' => $this->fixture->away_goals,
            'status' => $this->fixture->status,
            'status_turkish' => $this->fixture->status_turkish,
            'elapsed' => $this->fixture->elapsed,
            'is_live' => $this->fixture->is_live,
            'score' => $this->fixture->score,
            'updated_at' => $this->fixture->updated_at->toISOString()
        ];
    }

    /**
     * Get the event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'score.updated';
    }
}
