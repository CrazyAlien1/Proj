<?php

namespace App;

use App\Http\Requests\StoreGamePost;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function gamesCreated()
    {
        return $this->hasMany(Game::class, 'created_by');
    }

    public function gamesPlayed()
    {
        return $this->belongsToMany(Game::class, 'game_user', 'user_id');
    }

    public function createGame(StoreGamePost $post){
        $game = new Game();
        $game->created_by = $this->id;
        $game->type = $post->type;
        $game->name = $post->name;

        $game->save();
        //Depois de criar o jogo associo-o ao player que o criou na tabela de relacao
        $game->players()->attach($game->created_by);

        return $game;
    }

    public function joinGame()
    {

    }

}