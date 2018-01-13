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
            "winner"     =>   $games->groupBy('winner')->take(3),
        );

        return $statistics;
    }

    public function allUserStats($email){
        //logado
        $users = User::all();
        $currentUser= null;

        foreach ($users as $user){
            if($user->email === $email){
                $currentUser = $user;
                break;
            }
        }


        $statistics =array (
            "singlePlayer"  => $currentUser->gamesPlayed->where('type','multiplayer')->count(),
            "multiplayer"   => $currentUser->gamesPlayed->where('type','singleplayer')->count(),
            "totalPlayed"  =>  $currentUser->gamesPlayed->where('status','terminated')->count(),
            "totalWin"     =>  Game::all()->where('winner',$currentUser->nickname)->count(),
        );

        return $statistics;
    }
}
