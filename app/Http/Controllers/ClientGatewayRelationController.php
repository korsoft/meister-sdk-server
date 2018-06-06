<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ClientGateway;
use App\ClientGatewayRelation;
use App\User;
use App\Client;
use App\ClientUserRole;
use Illuminate\Support\Collection;

use Exception;
use Log;

class ClientGatewayRelationController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkClientAdmin', 
            ['only' => ['create', 'getByClientId','destroy','show','edit','update','test_connection']]);

    }

    public function store(Request $request){

        $userInSession = $request->user();

        if($userInSession->type == User::TYPE_SYSTEM_ADMIN)
            $client_id = $request->input('client_id');
        else 
            $client_id = $userInSession->client->client_id;

        $gateway_id= $request->input("gateway_id");
        if($client_id && $gateway_id)
        {

            $rels = ClientGatewayRelation::where("client_id",$client_id)->where("gateway_id",$gateway_id)->with("client")->with("gateway")->first();

            if($rels){
                return Response(json_encode(array("error"=>"The relation between client and gateway already exists")),402);
            }

            $cgr = new ClientGatewayRelation();
            $cgr->client_id=$client_id;
            $cgr->gateway_id = $gateway_id;

            if(!$cgr->save()){
                return Response(json_encode(array("error"=>"Cant save the relation between client and gateway")),402);
            }

            return $cgr;
        }else{
            return Response(json_encode(array("error"=>"Missing parameters")),402);
        }

    }

    public function destroy(Request $request,$id){

         $clientGateway =  ClientGatewayRelation::find($id);
         $user = $request->user();

         if($user->type == User::TYPE_CLIENT_ADMIN && $user->client_id != $clientGateway->client_id)
            throw new Exception("You can't delete the gateway from another client", 1);

        if($user->type == User::TYPE_SYSTEM_INTEGRATOR && $user->client_id != $clientGateway->client_id)
            throw new Exception("You can't delete the gateway from another client", 1);

        $clientGateway->delete();

        return $clientGateway;

    }

    // public function getByClientId(Request $request,$clientId){

    //     $userInSession = $request->user();

    //     if($userInSession->type == User::TYPE_SYSTEM_ADMIN)
    //         return ClientGatewayRelation::where("client_id",$clientId)->with("client")->with("gateway")->get();
    //     else 
    //         if($userInSession->client->id==$clientId)
    //             return ClientGatewayRelation::where("client_id",$clientId)->with("client")->with("gateway")->get();
    //         else
    //             return Response(json_encode(array("error"=>"not enougth priveleges")),402);

        
    // }

    // public function getByGatewayId(Request $request,$gatewayId){
    //     $userInSession = $request->user();

    //     if($userInSession->type == User::TYPE_SYSTEM_ADMIN)
    //         return ClientGatewayRelation::where("gateway_id",$gatewayId)->with("client")->with("gateway")->get();
    //     else 
    //         if($userInSession->client->id==$clientId)
    //             return ClientGatewayRelation::where("client_id",$userInSession->client->id)->where("gateway_id",$gatewayId)->with("client")->with("gateway")->get();
    //         else
    //             return Response(json_encode(array("error"=>"not enougth priveleges")),402);
        
    // }

    public function getByGatewayIdAndCurrentUser(Request $request,$gatewayId){
        $userInSession = $request->user();

        if($userInSession->type == User::TYPE_SYSTEM_ADMIN){
            return ClientGatewayRelation::where("gateway_id",$gatewayId)->with("client")->with("gateway")->get();
        }
        else {
            $clientsIds = ClientUserRole::where("user_id", $userInSession->id)->pluck("client_id")->toArray();

            return  ClientGatewayRelation::whereIn("client_id", $clientsIds)->where("gateway_id",$gatewayId)
                ->with("client")->with("gateway")->get();;

        }
        
    }

}
