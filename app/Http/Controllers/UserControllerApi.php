<?php

namespace App\Http\Controllers;


use App\Game;
use App\Mail\Block;
use App\Mail\Reactive;
use App\Mail\Remove;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\Users as UserResource;
use Illuminate\Support\Facades\Mail;

class UserControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(User::paginate(5));
    }

    public function allUsers(){
        return UserResource::collection(User::all());
    }

    public function allUsersOrderByName(){
        $users = User::orderBy('name')->get();

        return $users;
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $user = User::find($id);

        \Mail::to($user)->send(new Remove($user,$request->reason_remove));

        $user->delete();
        return $user;
    }

    public function deleteUser(Request $request,$id)
    {
        $user = User::find($id);

        \Mail::to($user)->send(new Remove($user,$request->reason_remove));

        $user->delete();
        //return $user;
    }

    public function block(Request $request, $id){
        $user = User::find($id);
        $user->reason_blocked = $request->reason_blocked;
        $user->blocked = 1;

        \Mail::to($user)->send(new Block($user));

        $user->save();

        return $user;
    }

    public function unBlock(Request $request,$id){
        $user = User::find($id);

        $user->blocked = 0;
        $user->reason_reactivated = $request->reason_reative;

        \Mail::to($user)->send(new Reactive($user));

        $user->save();

        return $user;
    }


}
