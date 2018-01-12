<?php

namespace App\Http\Resources;

use App\Game;
use Illuminate\Http\Resources\Json\Resource;

class Users extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'nickname' => $this->nickname,
            'email' => $this->email,
            'admin' => $this->admin,
//            'games_created' => Game::collection($this->gamesPlayer),
            'games_created' => $this->gamesCreated,
            'games_won' => $this->gamesPlayed->where('winner', $this->id)->count(),
            'games_played' => $this->gamesPlayed->count(),
            'blocked' => $this->blocked,
            'reason_blocked' => $this->reason_blocked,
            'reason_reactivated' => $this->reason_reactivated,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

}
