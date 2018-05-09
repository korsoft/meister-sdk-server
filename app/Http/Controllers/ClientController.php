<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Client;
use App\User;
use Exception;
use Log;
use App\ClientUserRole;

class ClientController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkClientAdmin');

        $this->middleware('checkSystemIntegrator', ['only' => ['index', 'store','destroy']]);

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
            return Client::get();
        } else if($user->type == User::TYPE_CLIENT_ADMIN || User::TYPE_SYSTEM_INTEGRATOR){
            $users = ClientUserRole::where("user_id",$user->id)->get(); 
            $userstoreturn = array();
            foreach($users as $uc){
                $userstoreturn[] = Client::where("id",$uc->client_id)->first();
            }

            return $userstoreturn;
        }

        return [];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
         $request->validate([
            'name' => 'required|max:200',
        ]);

        $client = new Client;
        $client->name = $request->input('name');
        $client->save();
        return $client;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Client::find($id);
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
         $request->validate([
            'name' => 'required|max:200',
        ]);

        $client = Client::find($id);
        
        if($client==null)
            throw new Exception("Client doesn't exist", 1);
            
        $client->name = $request->input('name');
        $client->save();

        return $client;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::find($id);
        
        if($client==null)
            throw new Exception("Client doesn't exist", 1);
            
        $client->delete();
        
        return $client;
    }
}
