<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Client;
use App\User;
use App\Role;
use App\ClientUserRole;
use Exception;

class ClientUserRoleController extends Controller
{

    public function __construct()
    {
         $this->middleware('checkSystemIntegrator');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ClientUserRole::get();
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
            'client_id' => 'required',
            'user_id' => 'required',
            'role_id' => 'required',
            'default' => 'required',
        ]);

        $client_id = $request->input("client_id");
        $user_id = $request->input("user_id");
        $role_id = $request->input("role_id");
        $default = $request->input("default");

        
        $client = Client::find($client_id);

        //**************Check if id's are in the database ********/

        if(!$client)
        {
            return response(json_encode(array("client"=>"The client_id doesn't exists")),422);
        }

    
        $user = User::find($user_id);

        if(!$user)
        {
            return response(json_encode(array("user"=>"The user_id doesn't exist")),422);
        }

        //**************Check if user has enough privileges ********/
        $session_user_level = $request->user()->type;
        //return error if user is TYPE_CLIENT_USER
        if($session_user_level==Role::TYPE_CLIENT_USER) 
        {
            return response(json_encode(array("user"=>"The user doesn't have enough privileges")),422);
        }else{
            //return error if user is TYPE_SYSTEM_INTEGRATOR or TYPE_CLIENT_ADMIN
            if($session_user_level==Role::TYPE_SYSTEM_INTEGRATOR || $session_user_level==Role::TYPE_CLIENT_ADMIN){
               $clientsForSessionUser = ClientUserRole::where('user_id',$request->user()->id)->where('client_id',$client_id)->first(); 
               if(!$clientsForSessionUser){
                   return response(json_encode(array("user"=>"The user doesn't have enough privileges")),422);
               }
            }
        }

        //The user SYSTEM_ADMIN is allowed to change everithing by default
        //*****************************************************/

        $role = Role::find($role_id);

        if(!$role)
        {
            return response(json_encode(array("role"=>"The role_id doesn't exist")),422);
        }
        //*****************************************************/

        //**************Check is combination of client_id and user_id is in the table ********/

        $clientUserRole=ClientUserRole::where("client_id",$client_id)->where("user_id",$user_id)->first();
        if($clientUserRole){
            return response(json_encode(array("clientuser"=>"The relation with client and user already exists")),422);
        }

        //******************************************************/
        

        //**************Creating new instance  of object********/
        $clienUserRole = new ClientUserRole();
        $clienUserRole->client_id=$request->input('client_id');
        $clienUserRole->user_id=$request->input('user_id');
        $clienUserRole->role_id=$request->input('role_id');
        $clienUserRole->default=$request->input('default');

       
        $clientUserRoleCount=ClientUserRole::where("user_id",$user_id)->get();
         // if the user  has another record with default set as true, 
         //check if the value is set to true, then find the default 
         // and set to false
        if(count($clientUserRoleCount)>0){
            if($default){
                $clientUserRoleTemp=ClientUserRole::where("user_id",$user_id)->where("default",true)->first();
                if(!$clientUserRoleTemp){
                    return response(json_encode(array("clientuser"=>"The old default model can't be retrieved")),422);
                }

                $clientUserRoleTemp->default=0;

                if(!$clientUserRoleTemp->save()){
                    return response(json_encode(array("clientuser"=>"The old default model can't be saved")),422);
                }
            }else{
                $clienUserRole->default=0;
            }
        }else{
            // if the user  doesn't have another record with default set as true, 
            //then set the new instance to true
            $clienUserRole->default=1;
        }

        if($clienUserRole->save())
        {
            return $clienUserRole;
        }

        return response(json_encode(array("clientuserrol"=>"The model can't be saved")),422);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ClientUserRole::find($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getByUserId($user_id)
    {
        return ClientUserRole::where('user_id',$user_id)->get();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getByClientAndUserId($client_id,$user_id)
    {
        return ClientUserRole::where('user_id',$user_id)->where('client_id',$client_id)->first();
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
            'role_id' => 'required',
            'default' => 'required',
        ]);

        $role_id = $request->input("role_id");
        $default = $request->input("default");

        
        

       

        //**************Check if user has enough privileges ********/
        $session_user_level = $request->user()->type;
        //return error if user is TYPE_CLIENT_USER
        if($session_user_level==Role::TYPE_CLIENT_USER) 
        {
            return response(json_encode(array("user"=>"The user doesn't have enough privileges")),422);
        }else{
            //return error if user is TYPE_SYSTEM_INTEGRATOR or TYPE_CLIENT_ADMIN
            if($session_user_level==Role::TYPE_SYSTEM_INTEGRATOR || $session_user_level==Role::TYPE_CLIENT_ADMIN){
               $clientsForSessionUser = ClientUserRole::where('user_id',$request->user()->id)->where('client_id',$client_id)->first(); 
               if(!$clientsForSessionUser){
                   return response(json_encode(array("user"=>"The user doesn't have enough privileges")),422);
               }
            }
        }
        //The user SYSTEM_ADMIN is allowed to change everithing by default
        //*****************************************************/

         //**************Check is role id's is in the database ********/
        $role = Role::find($role_id);

        if(!$role)
        {
            return response(json_encode(array("role"=>"The role_id doesn't exist")),422);
        }
        //*****************************************************/

        //**************Check is id is in the table ********/

        $clientUserRole=ClientUserRole::where("id",$id)->first();
        if(!$clientUserRole){
            return response(json_encode(array("clientuser"=>"The model doesn't exists")),422);
        }

        //******************************************************/

        $clientUserRole->role_id=$role_id ;


        //Check if the new value is true, then  find the other default record
        //and change the default property to false.
        if($default){
            $clientUserRoleTemp=ClientUserRole::where("id","!=",$clientUserRole->id)->where("default",true)->first();
            if($clientUserRoleTemp){
                $clientUserRoleTemp->default=0;
                if(!$clientUserRoleTemp->save()){
                    return response(json_encode(array("clientuser"=>"The old default model can't be saved")),422);
                }
            }
        }else{
            //Check if the new value is false but is actually the default value, then  
            //throw error.
            if($clientUserRole->default){
                return response(json_encode(array("clientuser"=>"The default value can't be false for all clients")),422);
            }
        }       
        
        $clientUserRole->default=$default;

        if($clientUserRole->save())
        {
            return $clientUserRole;
        }

        return response(json_encode(array("clientuserrol"=>"The model can't be saved")),422);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateByClientAndUserId(Request $request,$client_id,$user_id)
    {

        $request->validate([
            'role_id' => 'required',
            'default' => 'required',
        ]);

        $role_id = $request->input("role_id");
        $default = $request->input("default");

        
        $client = Client::find($client_id);

        //**************Check is id's are in the database ********/

        if(!$client)
        {
            return response(json_encode(array("client"=>"The client_id doesn't exists")),422);
        }

        $user = User::find($user_id);

        if(!$user)
        {
            return response(json_encode(array("user"=>"The user_id doesn't exist")),422);
        }

        //**************Check if user has enough privileges ********/
        $session_user_level = $request->user()->type;
        //return error if user is TYPE_CLIENT_USER
        if($session_user_level==Role::TYPE_CLIENT_USER) 
        {
            return response(json_encode(array("user"=>"The user doesn't have enough privileges")),422);
        }else{
            //return error if user is TYPE_SYSTEM_INTEGRATOR or TYPE_CLIENT_ADMIN
            if($session_user_level==Role::TYPE_SYSTEM_INTEGRATOR || $session_user_level==Role::TYPE_CLIENT_ADMIN){
               $clientsForSessionUser = ClientUserRole::where('user_id',$request->user()->id)->where('client_id',$client_id)->first(); 
               if(!$clientsForSessionUser){
                   return response(json_encode(array("user"=>"The user doesn't have enough privileges")),422);
               }
            }
        }
        //The user SYSTEM_ADMIN is allowed to change everithing by default
        //*****************************************************/

        $role = Role::find($role_id);

        if(!$role)
        {
            return response(json_encode(array("role"=>"The role_id doesn't exist")),422);
        }
        //*****************************************************/

        //**************Check is combination of client_id and user_id is in the table ********/

        $clientUserRole=ClientUserRole::where("client_id",$client_id)->where("user_id",$user_id)->first();
        if(!$clientUserRole){
            return response(json_encode(array("clientuser"=>"The relation with client and user doesn't exists")),422);
        }

        //******************************************************/

        $clientUserRole->role_id=$role_id ;
        $clientUserRole->default=$default;

        //Check if the new value is true, then  find the other default record
        //and change the default property to false.
        if($default){
            $clientUserRoleTemp=ClientUserRole::where("user_id",$user_id)->where("id","!=",$clientUserRole->id)->where("default",true)->first();
            if($clientUserRoleTemp){
                $clientUserRoleTemp->default=0;
                if(!$clientUserRoleTemp->save()){
                    return response(json_encode(array("clientuser"=>"The old default model can't be saved")),422);
                }
            }
        }else{
            //Check if the new value is false but is actually the default value, then  
            //throw error.
            $clientUserRoleTemp=ClientUserRole::where("user_id",$user_id)->where("id",$clientUserRole->id)->where("default",true)->first();
            if($clientUserRoleTemp){
                return response(json_encode(array("clientuser"=>"The default value can't be false for all clients")),422);
            }
        }        

        if($clientUserRole->save())
        {
            return $clientUserRole;
        }

        return response(json_encode(array("clientuserrol"=>"The model can't be saved")),422);
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $clientuserrole = ClientUserRole::find($id);

        if(!$clientuserrole)
        {
            return response(json_encode(array("clientuserrol"=>"The model does not exist")),422);;
        }

         //**************Check if user has enough privileges ********/
        $session_user_level = $request->user()->type;
        //return error if user is TYPE_CLIENT_USER
        if($session_user_level==Role::TYPE_CLIENT_USER) 
        {
            return response(json_encode(array("user"=>"The user doesn't have enough privileges")),422);
        }else{
            //return error if user is TYPE_SYSTEM_INTEGRATOR or TYPE_CLIENT_ADMIN
            if($session_user_level==Role::TYPE_SYSTEM_INTEGRATOR || $session_user_level==Role::TYPE_CLIENT_ADMIN){
               $clientsForSessionUser = ClientUserRole::where('user_id',$request->user()->id)->where('client_id',$clientuserrole->client_id)->first(); 
               if(!$clientsForSessionUser){
                   return response(json_encode(array("user"=>"The user doesn't have enough privileges")),422);
               }
            }
        }

        if($clientuserrole->default)
        {
            $clientuserroletemp = ClientUserRole::where('user_id',$clientuserrole->user_id)->get();

            if(count($clientuserroletemp)>1){
                foreach ($clientuserroletemp as $crrt) {
                    if($crrt->client_id!=$clientuserrole->client_id)
                    {
                        $crrt->default=true;
                        if(!$crrt->save())
                        {
                            return response(json_encode(array("clientuserrol"=>"The model's default propert can't be updated")),422);;
                        }

                        break;
                    }
                }
            }
        }
        
            
        $clientuserrole->delete();
        
        return $clientuserrole;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyByClientUserId($client_id,$user_id)
    {
        $clientuserrole = ClientUserRole::where("client_id",$client_id)->where("user_id",$user_id)->first();

        if(!$clientuserrole)
        {
            return response(json_encode(array("clientuserrol"=>"The model does not exist")),422);;
        }


        //**************Check if user has enough privileges ********/
        $session_user_level = $request->user()->type;
        //return error if user is TYPE_CLIENT_USER
        if($session_user_level==Role::TYPE_CLIENT_USER) 
        {
            return response(json_encode(array("user"=>"The user doesn't have enough privileges")),422);
        }else{
            //return error if user is TYPE_SYSTEM_INTEGRATOR or TYPE_CLIENT_ADMIN
            if($session_user_level==Role::TYPE_SYSTEM_INTEGRATOR || $session_user_level==Role::TYPE_CLIENT_ADMIN){
               $clientsForSessionUser = ClientUserRole::where('user_id',$request->user()->id)->where('client_id',$client_id)->first(); 
               if(!$clientsForSessionUser){
                   return response(json_encode(array("user"=>"The user doesn't have enough privileges")),422);
               }
            }
        }

        if($clientuserrole->default)
        {
            $clientuserroletemp = ClientUserRole::where('user_id',$clientuserrole->user_id)->get();

            if(count($clientuserroletemp)>1){
                foreach ($clientuserroletemp as $crrt) {
                    if($crrt->client_id!=$clientuserrole->client_id)
                    {
                        $crrt->default=true;
                        if(!$crrt->save())
                        {
                            return response(json_encode(array("clientuserrol"=>"The model's default propert can't be updated")),422);;
                        }

                        break;
                    }
                }
            }
        }
        
            
        $clientuserrole->delete();
        
        return $clientuserrole;
    }
}
