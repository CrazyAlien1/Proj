<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;

class StatisticsControllerApi extends Controller
{
    //


    public function getStatistics(){
        $statistics =array (
            "singlePlayer"  => Game::all()->where('type','multiplayer')->count(),
            "multiplayer"   => Game::all()->where('type','singleplayer')->count(),
            "totalPlayed"  =>  Game::all()->where('status','terminated')->count(),
        );

        return $statistics;
    }
}
