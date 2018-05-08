<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use App\User;
use App\Role;
use App\ClientUserRole;


use Log;
use Exception;

class UserController extends Controller
{
    public function __construct()
    {
        //$this->middleware('checkClientAdmin', ['only' => ['index', 'types','store','destroy','update']]);

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
            return User::with('clients')->with("clients.role")->get();
        } else if($user->type == User::TYPE_CLIENT_ADMIN || User::TYPE_SYSTEM_INTEGRATOR){
            return User::where('client_id',$user->client_id)->with('clients')->with("clients.role")->get();
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
                    "id" => User::TYPE_SYSTEM_INTEGRATOR,
                    "name" => "System Integrator"
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
        } if($user->type == User::TYPE_SYSTEM_INTEGRATOR){
            return [
                [
                    "id" => User::TYPE_SYSTEM_INTEGRATOR,
                    "name" => "System Integrator"
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
        }
        else if($user->type == User::TYPE_CLIENT_ADMIN){
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
        
        $user = new User;
        $user->name = $request->input('email');
        $user->email = $request->input('email');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->password = bcrypt(User::DEFAULT_PASSWORD);

      

        if($request->input('type')!= User::TYPE_SYSTEM_ADMIN  && !$request->input('client_id')){
            throw new Exception("Missing parameters", 1);
        }


        $client_role = $userInSession->clientRole($request->input('client_id'));

        if($request->input('type') == User::TYPE_SYSTEM_ADMIN)
        {
            $user->type = $request->input('type');
            $user->save();    
            return $user;
        }
        else if( $client_role && $client_role->value >= $request->input('type') && $client_role->value> User::TYPE_CLIENT_USER ){

            if( $userInSession->type != User::TYPE_SYSTEM_ADMIN ){
                if( !$client_role || $client_role->type == User::TYPE_CLIENT_USER){
                    throw new Exception("Can't create a users due to privileges", 1);
                } 

                // if(!ClientUserRole::where('user_id',$userInSession->id)->first())
                // {
                //     throw new Exception("Can't create a user for this client", 1);
                // }
            }

             DB::beginTransaction();
             if(!$user->save())
             {
                 DB::rollBack();
                 throw new Exception("Can't create an user in database", 1);
             }

             $clientUserRole = new ClientUserRole();

             $clientUserRole->client_id =  $request->input('client_id');

             $clientUserRole->user_id= $user->id;
             
             $clientUserRole->default= true;

             $role = Role::where("value",$request->input('type'))->first();

             if(!$role){
                  DB::rollBack();
                 throw new Exception("Can't get the Role in database", 1);
             }

             $clientUserRole->role_id = $role->id;

             if($clientUserRole->save())
             {
                  DB::commit();
                  return $user;
             }else
             {
                  DB::rollBack();
                 throw new Exception("Can't save the relation in database", 1);
             }
        }else if($client_role && $client_role->value<= User::TYPE_CLIENT_USER)
        {
             throw new Exception("The client user can't create another user", 1);
        }else if (!$client_role){
             throw new Exception("Can't create user for this client", 1);
        }

        return null;
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

    public function update_my_user(Request $request, $id){

        $userInSession = $request->user();

        $request->validate([
            'email' => 'required|max:100',
            'first_name' => 'required|max:120',
            'last_name' => 'required|max:120'
        ]);

        if($id != $userInSession->id)
            throw new Exception("You can't update this User", 1);

        $user = User::find($id);
        $user->name = $request->input('email');
        $user->email = $request->input('email');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        
        if($request->input('type') == User::TYPE_SYSTEM_ADMIN)
        {
            DB::beginTransaction();
            $permisssions = ClientUserRole::where("user_id",$id)->get();

            foreach($permisssions as $p){
                if(!$p->delete())
                {
                    DB:rollback();
                    throw new Exception("Can't remove older permission", 1);
                }
            }
         
            $user->type = $request->input('type');
            $user->save();   
            DB::commit();

            return $user;

        }
        else if($request->input('type') == User::TYPE_SYSTEM_INTEGRATOR  || $request->input('type') == User::TYPE_CLIENT_ADMIN){

            if($request->input('client_id')==null && $request->input('user_id')==null){
                throw new Exception("Missing parameters", 1);
            }

            if(!ClientUserRole::where('user_id',$userInSession->id)->first())
            {
                throw new Exception("Can't create a user for this client", 1);
            }

            DB::beginTransaction();
             if(!$user->save())
             {
                 DB::rollBack();
                 throw new Exception("Can't create an user in database", 1);
             }

             $clientUserRole = new ClientUserRole();

             $clientUserRole->client_id =  $request->input('client_id');

             $clientUserRole->user_id= $request->input('client_id');
            

             $role = Role::where("value",$request->input('type'))->first();

             if(!$role){
                  DB::rollBack();
                 throw new Exception("Can't get the Role in database", 1);
             }

             $clientUserRole->role_id = $role->id;

             $clientUserRole->default= true;
             if($clientUserRole->default){
                 $permission = ClientUserRole::where("user_id",$id)->where("default",true)->first();
                 $permission->default=false;

                 if(!$permission->save())
                 {
                       DB::rollBack();
                     throw new Exception("Can't update the default state in database", 1);
                 }
             }

             if($clientUserRole->save())
             {
                  DB::commit();
                  return $user;
             }else
             {
                  DB::rollBack();
                 throw new Exception("Can't save the relation in database", 1);
             }
        }

        $user->save();

        return $user;

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
            throw new Exception("You can't edit an user type system admin", 1);

        if($request->input('type')>=User::TYPE_SYSTEM_INTEGRATOR && $userInSession->type < User::TYPE_SYSTEM_INTEGRATOR)
            throw new Exception("You can't edit an user type system integrator", 1);

        if($request->input('type')>=User::TYPE_CLIENT_ADMIN && $userInSession->type < User::TYPE_CLIENT_ADMIN)
            throw new Exception("You can't edit an user type client admin", 1);

        if( $userInSession->type < User::TYPE_CLIENT_USER)
            throw new Exception("You can't edit an user", 1);

        $user = User::find($id);
        $user->name = $request->input('email');
        $user->email = $request->input('email');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->password = bcrypt(User::DEFAULT_PASSWORD);

        if($request->input('type') == User::TYPE_SYSTEM_ADMIN){
            $user->type = $request->input('type');
        }
        else if($userInSession->type == User::TYPE_SYSTEM_INTEGRATOR)
            $user->client_id = $request->input('client_id');
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
        
        if($user->type!=User::TYPE_SYSTEM_ADMIN)
        {
            $band= false;

            $userPermission = ClientUserRole::with('role')->where("user_id",$user->id)->get();

            foreach($userPermission  as $up)
            {
                $userToDestroyClient = ClientUserRole::with('role')->where("user_id",$userToDestroy->id)->get();
                foreach($userToDestroyClient  as $utd){
                    if($utd->client_id==$up->client_id)
                    {
                        if($utd->role->value<=$up->role->value && $up->role->value>=User::TYPE_CLIENT_ADMIN){
                            $band=true;
                            break;
                        }else{
                            throw new Exception("Can't delete relation with this user due to permission", 1);    
                        }
                    }
                }

                if($band)
                {
                    break;
                }
            }

            
            if($band){
                DB::beginTransaction();
                foreach($userToDestroyClient  as $utd){
                    if(!$utd->delete())
                    {
                        DB::rollBack();
                        throw new Exception("Can't delete relation with this user", 1);
                    }
                }
                $user->delete();
                DB::commit();
            }
        }else{
            $userToDestroyClient = ClientUserRole::where("user_id",$userToDestroy->id)->get();
            DB::beginTransaction();
            foreach($userToDestroyClient  as $utd){
                if(!$utd->delete())
                {
                    DB::rollBack();
                    throw new Exception("Can't delete relation with this user", 1);
                }
            }
            $user->delete();
            DB::commit();
        }

        return $user;
            
    }
}
