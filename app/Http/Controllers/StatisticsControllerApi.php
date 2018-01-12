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
        $games =  Game::all();

        $statistics =array (
            "singlePlayer"  => Game::all()->where('type','multiplayer')->count(),
            "multiplayer"   => Game::all()->where('type','singleplayer')->count(),
            "totalPlayed"  =>  Game::all()->where('status','terminated')->count(),
            "winner"     =>   $games->groupBy('winner')->take(3)->count(),
        );

        return $statistics;
    }

    public function allUserStats($email){
        //logado
        $user = User::find($email);

        $statistics =array (
            "singlePlayer"  => $user->gamesPlayed->where('type','multiplayer')->count(),
            "multiplayer"   => $user->gamesPlayed->where('type','singleplayer')->count(),
            "totalPlayed"  =>  $user->gamesPlayed->where('status','terminated')->count(),
            "totalWin"     =>  Game::all()->where('winner',$user->nickname)->count(),
        );

        return $statistics;
    }
}
