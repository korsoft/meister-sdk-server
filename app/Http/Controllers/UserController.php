<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use App\User;
use App\Role;
use App\ClientUserRole;


use Log;
use Exception;


/**
 * @group User Controller
 *
 * APIs for user's client management
 */

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkClientAdmin', ['only' => ['index', 'types','store','destroy','update']]);

    }

     /**
     * Display a list of user's Client based on User logged permissions:
     * 
     * For a System Admin all users will be displayed. 
     * For Client Admin  or System Integrator only users that belogns to the same 
     * organization will be displayed with all the roles.
     * 
     * 
     * @authenticated
     */
    public function index(Request $request)
    {

        $user = $request->user();

        Log::info("User in session",["array"=>$user]);

        if($user->type == User::TYPE_SYSTEM_ADMIN){
            return User::with('clients')->with("clients.role")->get();
        } else if($user->type == User::TYPE_CLIENT_ADMIN || User::TYPE_SYSTEM_INTEGRATOR){
            $users = ClientUserRole::where("client_id",$user->client->client_id)->get(); 
            $userstoreturn = array();
            foreach($users as $uc){
                $find = array_filter($userstoreturn,function ($k) use($uc){
                    if($k->id==$uc->user_id)
                    {
                        return true;
                    }
                    return false;
                });
                if(count($find)==0){
                    $userstoreturn[] = User::where("id",$uc->user_id)->with('clients')->with("clients.role")->first();
                }
            }
            

            return $userstoreturn;
        } 
        return [];
    }

    /**
     * Display a logged user type. 
     * Depending on user logged permission it may display all types or only a valid subset of them. 
     * 
     * @authenticated
     */
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


  
    public function create(Request $request)
    {
        throw new Exception("Error Processing Request", 1);
                        
    }

    /**
     * Store a newly created user in database with the given values.
     *
     * @bodyParam  email string required The email of the user.
     * @bodyParam  first_name string required The first name  of the user.
     * @bodyParam  last_name string required The last name  of the user.
     * @bodyParam  type int required the type of the user 
     * @bodyParam  password string required The password of the user
     */
    public function store(Request $request)
    {
        $userInSession = $request->user();

        $request->validate([
            'email' => 'required|max:100',
            'first_name' => 'required|max:120',
            'last_name' => 'required|max:120',
            'type' => 'required',
            'password' => 'required'
        ]);

        if($request->input('type')==User::TYPE_SYSTEM_ADMIN && $userInSession->type != User::TYPE_SYSTEM_ADMIN)
            throw new Exception("You can't create an user type system admin", 1);
        
        $user = new User;
        $user->name = $request->input('email');
        $user->email = $request->input('email');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->password = bcrypt($request->input('password'));

      

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
     * Display the specified user that matches with id.
     *
     * @queryParam  int required $id
     * 
     */
    public function show($id)
    {
        return User::find($id);
    }

    
    public function edit($id)
    {
        throw new Exception("Error Processing Request", 1);
        
    }

    /**
     * Updates the user logged in session passing the id as security meassure.
     *
     * @queryParam  int required id 
     * @bodyParam  email string required The email of the user.
     * @bodyParam  first_name string required The first name  of the user.
     * @bodyParam  last_name string required The last name  of the user.
     */
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
        
        if($request->input('password') &&  strlen(trim($request->input('password')))>0){
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return $user;

    }
    

    /**
     * Updates the user that matches with the given id. The success of the operation
     * will depend on the user permissions
     *
     * @queryParam int required id 
     * @bodyParam  email string required The email of the user.
     * @bodyParam  first_name string required The first name  of the user.
     * @bodyParam  last_name string required The last name  of the user.
     */
    public function update(Request $request, $id)
    {
       $userInSession = $request->user();

        $request->validate([
            'email' => 'required|max:100',
            'first_name' => 'required|max:120',
            'last_name' => 'required|max:120'
        ]);

        $user = User::find($id);

        $band = false;
        if( $userInSession->type == User::TYPE_SYSTEM_ADMIN)
        {
            $band = true;
        }else{
            $clients = $userInSession->clients();

            foreach($clients as $c){
                $uclients = $user->clients();
                    
                foreach($uclients as $uc){
                    if($c->client_id == $uc->client_id){
                        if($c->role->value>=$uc->role->value && $c->role->value> User::TYPE_CLIENT_USER){
                            $band=true;
                            break;
                        }
                    }
                }
                if($band)
                {
                    break;
                }
            }
        }



        if(!$band){
             throw new Exception("Can't modify the user", 1);
        }

        $user->name = $request->input('email');
        $user->email = $request->input('email');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        if($request->input('password') != null){
            $user->password = bcrypt($request->input('password'));    
        }
        

        $user->save();

        return $user;

    }

    /**
     * Remove from database the user that watches with id.
     *
     * @queryParam  int required $id
     * 
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
                $userToDestroy->delete();
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
            $userToDestroy->delete();
            DB::commit();
        }

        return $user;
            
    }

    /**
     * Change the deafult user for the especified client based on cliend_id
     *
     * @queryParam  int required id
     * 
     */
    public function changeDef(Request $request, $client_id){

        $userInSession = $request->user();

        if($userInSession->type == User::TYPE_SYSTEM_ADMIN){
            return $userInSession;
        }

        $oldDef = ClientUserRole::where("user_id",$userInSession->id)->where("default",true)->first();

        if(!$oldDef)
        {
              throw new Exception("Can't find the default client for this user", 1);
        }

        $oldDef->default=false;;

        $newDef = ClientUserRole::where("user_id",$userInSession->id)->where("client_id",$client_id)->first();

        if(!$newDef )
        {
            throw new Exception("Can't find the client for this user", 1);
        }

        if($newDef->id == $oldDef->id)
        {
            $userInSession->clients=$userInSession->clients;
            $userInSession->client=$userInSession->client;
            return  $userInSession;
        }

        $newDef->default = true;

        DB::beginTransaction();
        if( !$oldDef->save() || !$newDef->save())
        {
            DB::rollBack();
            throw new Exception("Can't save in the database", 1);
        }

        DB::commit();
        $userInSession->clients=$userInSession->clients;
        $userInSession->client=$userInSession->client;
        return  $userInSession;
        
    }
}
