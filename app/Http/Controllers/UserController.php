<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

use Log;
use Exception;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkClientAdmin', ['only' => ['index', 'types','store','destroy']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $user = $request->user();

        Log::info("User in session",["array"=>$user]);

        if($user->type == User::TYPE_SYSTEM_ADMIN){
            return User::with('client')->get();
        } else if($user->type == User::TYPE_CLIENT_ADMIN){
            return User::where('client_id',$user->client_id)->with('client')->get();
        } 
        return [];
    }

    public function types(Request $request){
        $user = $request->user();

        Log::info("User in session",["array"=>$user]);

        if($user->type == User::TYPE_SYSTEM_ADMIN){
            return [
                [
                    "id" => User::TYPE_SYSTEM_ADMIN,
                    "name" => "System Admin"
                ],
                [
                    "id" => User::TYPE_CLIENT_ADMIN,
                    "name" => "Client Admin"  
                ],
                [
                    "id" => User::TYPE_CLIENT_USER,
                    "name" => "Client User"
                ]            
            ];
        } else if($user->type == User::TYPE_CLIENT_ADMIN){
            return [
                [
                    "id" => User::TYPE_CLIENT_ADMIN,
                    "name" => "Client Admin"  
                ],
                [
                    "id" => User::TYPE_CLIENT_USER,
                    "name" => "Client User"
                ]            
            ];
        } else if($user->type == User::TYPE_CLIENT_USER){
            return [];
        }
        return [];
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        throw new Exception("Error Processing Request", 1);
                        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userInSession = $request->user();

        $request->validate([
            'email' => 'required|max:100',
            'first_name' => 'required|max:120',
            'last_name' => 'required|max:120',
            'type' => 'required'
        ]);

        if($request->input('type')==User::TYPE_SYSTEM_ADMIN && $userInSession->type != User::TYPE_SYSTEM_ADMIN)
            throw new Exception("You can't create an user type system admin", 1);

        if($request->input('type')==User::TYPE_CLIENT_ADMIN && $userInSession->type == User::TYPE_CLIENT_USER)
            throw new Exception("You can't create an user type client admin", 1);

        $user = new User;
        $user->name = $request->input('email');
        $user->email = $request->input('email');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->password = bcrypt(User::DEFAULT_PASSWORD);
        $user->type = $request->input('type');

        if($request->input('type') == User::TYPE_SYSTEM_ADMIN)
            $user->client_id = null;
        else if($userInSession->type == User::TYPE_SYSTEM_ADMIN)
            $user->client_id = $request->input('client_id');
        else if($userInSession->type != User::TYPE_SYSTEM_ADMIN)
            $user->client_id = $userInSession->client_id;

        $user->save();

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        throw new Exception("Error Processing Request", 1);
        
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
       $userInSession = $request->user();

        $request->validate([
            'email' => 'required|max:100',
            'first_name' => 'required|max:120',
            'last_name' => 'required|max:120',
            'type' => 'required'
        ]);

        if($request->input('type')==User::TYPE_SYSTEM_ADMIN && $userInSession->type != User::TYPE_SYSTEM_ADMIN)
            throw new Exception("You can't create an user type system admin", 1);

        if($request->input('type')==User::TYPE_CLIENT_ADMIN && $userInSession->type == User::TYPE_CLIENT_USER)
            throw new Exception("You can't create an user type client admin", 1);

        $user = User::find($id);
        $user->name = $request->input('email');
        $user->email = $request->input('email');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->password = bcrypt(User::DEFAULT_PASSWORD);
        $user->type = $request->input('type');

        if($request->input('type') == User::TYPE_SYSTEM_ADMIN)
            $user->client_id = null;
        else if($userInSession->type == User::TYPE_SYSTEM_ADMIN)
            $user->client_id = $request->input('client_id');
        else if($userInSession->type != User::TYPE_SYSTEM_ADMIN)
            $user->client_id = $userInSession->client_id;

        $user->save();

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $userToDestroy = User::find($id);

        $user = $request->user();

        if($user->type == User::TYPE_CLIENT_USER)
            throw new Exception("You can't delete this user", 1);
        
        if($user->type == User::TYPE_CLIENT_ADMIN && $user->client_id != $userToDestroy->client_id)
            throw new Exception("You can't delete the user from another user", 1);

        if($user->type < $userToDestroy->type)
            throw new Exception("You can't delete this user", 1);

        $user->delete();

        return $user;
            
            
    }
}
