<?php

namespace App\Http\Controllers;

use App\Game;
use App\Http\Requests\StoreGamePost;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\Games as GamesResource;

class GameControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function allGames(){
        return GamesResource::collection(Game::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGamePost $post)
    {
        $user = User::find($post->userID);

        $game = new Game;
        $game->type = $post->type;
        $game->name = $post->name;
        $game->rows = $post->rows;
        $game->columns = $post->cols;

        $game->owner()->associate($user);

        $game->save();

        return new GamesResource(Game::find($game->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $game = Game::find($request->id);


        $game->status = $request->status;
        $game->type = $request->type;

        $game->total_players = count($request->players);

        for( $i = 0; $i < count($request->players); $i++){
            $user = User::find($request->players[$i]);

            if($user != null){

                $game->players()->attach($user->id);
            }
        }

        $winner = User::find($request->winner);

        $game->winner()->associate($winner);
        $game->save();
        //adicionar o winner á relacao e os players
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //if this game belongs to the user
        $game = Game::find($id);
        $game->delete();
    }

    public function verify(Request $request){
        return $request;
    }
}
