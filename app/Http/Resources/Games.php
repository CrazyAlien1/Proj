<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Games extends Resource
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
            'name' => $this->name,
            'status' => $this->status,
            'created_by' => $this->owner,
            'winner' => $this->winner(),
            'total_players' => $this->total_players,
            'cols' => $this->columns,
            'rows' => $this->rows,
            'players' => $this->players->pluck('id')->toArray(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function users()
    {
        return $this->belongsToMany(Users::class);
    }
}
