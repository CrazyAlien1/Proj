<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{

    public function owner()
    {
        //FK do User na tabela games
        return $this->belongsTo(User::class, 'created_by');
    }

    //
    public function players()
    {
        //FK do jogo na tabela de relacionamento do game_user
        return $this->belongsToMany(User::class, 'game_user', 'game_id');
    }
    
    public function winner()
    {
        //Jogo tem um Fk do user que venceu
        return $this->belongsTo(User::class, 'winner');
    }

    public function addPlayer()
    {

    }

}
