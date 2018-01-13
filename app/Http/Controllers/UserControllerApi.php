<?php

namespace App\Http\Controllers;


use App\Game;
use App\Image;
use App\Mail\Block;
use App\Mail\Reactive;
use App\Mail\Remove;
use App\Mail\Reset;
use App\Mail\Welcome;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\Users as UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $users = DB::table('users')->select('name', 'email', 'nickname')->orderBy('name')->get();
        return $users;
    }

    public function usersGamesPlayedStats(){
        $usersPlayed = DB::table('users')
                    ->leftJoin('game_user', 'users.id', '=', 'game_user.user_id', 'outer')
                    ->join('games', 'games.id', '=', 'game_user.game_id')
                    ->groupBy('game_user.user_id', 'games.type')
                    ->select('users.name', 'users.email', 'users.nickname', 'games.type', DB::raw('count(*) as totalGames, games.type'), DB::raw('count(games.winner) as totalWins, games.type'))
                    ->orderBy('users.name')
                    ->get();
        return $usersPlayed;
    }

    public function getUserDetails($email){
        $users = User::all();
        $currentUser= null;

        foreach ($users as $user){
            if($user->email === $email){
                $currentUser = $user;
                break;
            }
        }

        return $currentUser;
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
        $user = new User;
        $user->name = $request->name;
        $user -> email = $request->email;
        $user->nickname = $request->username;
        $user->password = Hash::make($request -> password);

        \Mail::to($user)->send(new Welcome($user));

        $user->ative = 0;

        $user->save();

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
    public function update(Request $request, $email)
    {
        $users = User::all();
        $currentUser= null;

        foreach ($users as $user){
            if($user->email === $email){
                $currentUser = $user;
                break;
            }
        }

        $currentUser->name = $request->name;
        $currentUser->email = $request->email;
        $currentUser->nickname = $request->nickname;
        $currentUser->password = Hash::make($request -> password);

        $currentUser->save();
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

    public function removeAccount($email)
    {
        $users = User::all();
        $currentUser= null;

        foreach ($users as $user){
            if($user->email === $email){
                $currentUser = $user;
                break;
            }
        }

        $currentUser->delete();
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

    public function getAuthUser($email){
        $users = User::all();
        $currentUser= null;

        foreach ($users as $user){
            if($user->email === $email){
                $currentUser = $user;
                break;
            }
        }

        return $currentUser->admin;
    }

    public function disableUser($email){
        $users = User::all();
        $currentUser= null;
        foreach ($users as $user){
            if($user->email === $email){
                $currentUser = $user;
                break;
            }
        }
        $currentUser->ative = 0;
        $currentUser->save();
    }

    public function upload(Request $request){
        if ($request->hasFile('image'))
        {
            $num = (Image::all()->count() - 2);
            $imageName = $num +1;
            console.log($request->image);
            $image = new Image();
            $image->path = $imageName.'.png' ;
            $image-> face = 'tile';
            $image -> active = 1;
            $image ->save();
            Storage::disk('upload')->putFileAs($imageName, $request->file('image'), $image->path);
        }
    }

    public function reactiveUser($id){
        $user = User::find($id);
        $user->ative = 1;
        $user->save();
        return redirect()->route('/');
    }

    public function resetPassword(Request $request,$email){
        $users = User::all();
        $currentUser= null;
        foreach ($users as $user){
            if($user->email === $email){
                $currentUser = $user;
                break;
            }
        }
        if (Hash::check($request->currentPassword, $currentUser->password)) {
            $currentUser->password = bcrypt($request->newPassword);
            $currentUser ->save();
            return response()->json(['message'=>'Password reset Sucessefully'], 200);
        }
        return response()->json(['message'=>'Verify if you insert the correct old password' ], 400);
    }
    public function passwordReset($email){
        $users = User::all();
        $currentUser= null;
        foreach ($users as $user){
            if($user->email === $email){
                $currentUser = $user;
                break;
            }
        }

        \Mail::to($currentUser)->send(new Reset($currentUser));
    }
}
