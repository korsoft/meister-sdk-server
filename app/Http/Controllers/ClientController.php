<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Client;
use App\User;
use Exception;
use Log;
use App\ClientUserRole;
/**
 * @group Client Controller
 *
 * APIs for client management
 */
class ClientController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkClientAdmin');

        $this->middleware('checkSystemIntegrator', ['only' => ['index', 'store','destroy']]);

    }

    /**
     * Display a list of Clients based on User's permissions:
     * 
     * For a System Admin all clients will be displayed. 
     * For Client Admin  or System Integrator only users that belogns to the same 
     * organization will be displayed with all the roles.
     * 
     * 
     * 
     * @authenticated
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

  
    public function create()
    {
        throw new Exception("Error Processing Request", 1);     
    }

    /**
     * Store a newly created client in the datavase.
     *
     * @bodyParam name string required The name of the Client.
     * @bodyParam sap_number string required the sap number.
     * 
     */
    public function store(Request $request)
    {
         $request->validate([
            'name' => 'required|max:200',
            'sap_number' => 'required|max:3'
        ]);

        $client = new Client;
        $client->name = $request->input('name');
        $client->sap_number = $request->input('sap_number');
        $client->save();
        return $client;
    }

    /**
     * Display the specified client  wich matches to the given id paramater.
     *
     * @queryParam  int required id
     * 
     */
    public function show($id)
    {
        return Client::find($id);
    }

    
    public function edit($id)
    {
        throw new Exception("Error Processing Request", 1);
        
    }

    /**
     * Update the specified resource by id using the given values.
     *
     * @queryParam int required id 
     * @bodyParam name string required The name of the Client.
     * @bodyParam sap_number string required the sap number.
     */
    public function update(Request $request, $id)
    {
         $request->validate([
            'name' => 'required|max:200',
            'sap_number' => 'required|max:3'
        ]);

        $client = Client::find($id);
        
        if($client==null)
            throw new Exception("Client doesn't exist", 1);
            
        $client->name = $request->input('name');
        $client->sap_number = $request->input('sap_number');
        $client->save();

        return $client;
    }

    /**
     * Remove from database the client especified by id.
     *
     * @queryParam  int  required id
     * 
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
