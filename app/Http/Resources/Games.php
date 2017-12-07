<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Games extends ResourceColl
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'status' => $this->status,
            'created_by' => $this->owner,
            'winner' => $this->winner(),
            'total_players' => $this->total_players,
            'players' => $this->players,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
