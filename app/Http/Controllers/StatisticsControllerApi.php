<?php

namespace App\Http\Controllers;

use App\Game;
use App\User;
use Illuminate\Http\Request;

class StatisticsControllerApi extends Controller
{
    //


    public function getStatistics(){
        //admin //TODO corrir estatisticas do admin
        $statistics =array (
            "singlePlayer"  => Game::all()->where('type','multiplayer')->count(),
            "multiplayer"   => Game::all()->where('type','singleplayer')->count(),
            "totalPlayed"  =>  Game::all()->where('status','terminated')->count(),
        );

        return $statistics;
    }

    public function allStats(){
        //nao logado
        $statistics =array (
            "singlePlayer"  => Game::all()->where('type','multiplayer')->count(),
            "multiplayer"   => Game::all()->where('type','singleplayer')->count(),
            "totalPlayed"  =>  Game::all()->where('status','terminated')->count(),
            "winner"     =>    Game::all()->groupBY('winner')->take(3)->get(),
        );

        return $statistics;
    }

    public function allUserStats(User $user){
        //logado
        //TODO ver com o bot
        $statistics =array (
            "singlePlayer"  => $user->gamesPlayed()->where('type','multiplayer')->count(),
            "multiplayer"   => $user->gamesPlayed()->where('type','singleplayer')->count(),
            "totalPlayed"  =>  $user->gamesPlayed()->where('status','terminated')->count(),
            "totalWin"     =>  Game::all()->where('winner',$user->name)->count(),
        );

        return $statistics;
    }
}
