<?php

namespace App\Events;

use App\Models\Demande;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrivateDemande implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $demande;
    public $numDemande;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Demande $demande,$numDemande)
    {

        $this->demande = $demande;
        $this->numVidange = $numDemande;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('privatedemande.'.$this->demande->admin_id);
    }
}
