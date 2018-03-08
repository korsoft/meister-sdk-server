<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ClientGateway;
use App\User;

use Exception;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use kamermans\OAuth2\GrantType\PasswordCredentials;
use kamermans\OAuth2\OAuth2Middleware;

use Log;

class ClientGatewayController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkClientAdmin', 
            ['only' => ['create', 'store','destroy','show','edit','update','test_connection']]);

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
            return ClientGateway::with('client')->get();
        } else if($user->type == User::TYPE_CLIENT_ADMIN){
            return ClientGateway::where('client_id',$user->client_id)->with('client')->get();
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
        $userInSession = $request->user();

        $request->validate([
            'name' => 'required|max:120',
            'url' => 'required',
            'auth_type' => 'required'
        ]);

        $clientGateway = new ClientGateway;
        $clientGateway->name = $request->input('name');
        $clientGateway->url = $request->input('url');
        $clientGateway->username = $request->input('username');
        $clientGateway->password = $request->input('password');
        $clientGateway->auth_type = $request->input('auth_type');
        $clientGateway->digest = $request->input('digest');
        $clientGateway->consumer_key = $request->input('consumer_key');
        $clientGateway->consumer_secret = $request->input('consumer_secret');
        $clientGateway->token = $request->input('token');
        $clientGateway->token_secret = $request->input('token_secret');
        $clientGateway->client_id_for_oauth2 = $request->input('client_id_for_oauth2');
        $clientGateway->client_secret_for_oauth2 = $request->input('client_secret_for_oauth2');
        $clientGateway->auth_url_for_oauth2 = $request->input('auth_url_for_oauth2');

        if($userInSession->type == User::TYPE_SYSTEM_ADMIN)
            $clientGateway->client_id = $request->input('client_id');
        else 
            $clientGateway->client_id = $userInSession->client_id;

        $clientGateway->save();

        return $clientGateway;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $clientGateway = ClientGateway::find($id);

        if($request->user()->type == User::TYPE_SYSTEM_ADMIN)
            return $clientGateway;
        else if($request->user()->type != User::TYPE_SYSTEM_ADMIN && 
                $clientGateway->client_id == $request->user()->client_id)
            return $clientGateway;

        return null;
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
            'name' => 'required|max:120',
            'url' => 'required',
            'auth_type' => 'required'
        ]);

        $clientGateway = ClientGateway::find($id);
        $clientGateway->name = $request->input('name');
        $clientGateway->url = $request->input('url');
        $clientGateway->username = $request->input('username');
        $clientGateway->password = $request->input('password');
        $clientGateway->auth_type = $request->input('auth_type');
        $clientGateway->digest = $request->input('digest');
        $clientGateway->consumer_key = $request->input('consumer_key');
        $clientGateway->consumer_secret = $request->input('consumer_secret');
        $clientGateway->token = $request->input('token');
        $clientGateway->token_secret = $request->input('token_secret');
        $clientGateway->client_id_for_oauth2 = $request->input('client_id_for_oauth2');
        $clientGateway->client_secret_for_oauth2 = $request->input('client_secret_for_oauth2');
        $clientGateway->auth_url_for_oauth2 = $request->input('auth_url_for_oauth2');

        if($userInSession->type == User::TYPE_SYSTEM_ADMIN)
            $clientGateway->client_id = $request->input('client_id');

        $clientGateway->save();

        return $clientGateway;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $clientGateway = ClientGateway::find($id);

        $user = $request->user();

        if($user->type == User::TYPE_CLIENT_ADMIN && $user->client_id != $clientGateway->client_id)
            throw new Exception("You can't delete the gateway from another client", 1);

        $clientGateway->delete();

        return $clientGateway;
    }

    public function test_connection(Request $request, $id){
        $clientGateway = ClientGateway::find($id);

        if(!$clientGateway)
            throw new Exception("Error the gateway doesn't exist", 1);

        try {
            
            $response = self::response_connection($clientGateway);

           if($response->getStatusCode()!="200")
               throw new Exception("Connection failure", 1);

            return [];

        } catch(\GuzzleHttp\Exception\ClientException $e){
            Log::info("ClientException",["result" => $e]);
            throw new Exception("Connection failure", 1);
        } catch(Exception $e){
            Log::info("Exception",["result" => $e]);
            throw new Exception("Connection failure", 1);
        }
    }

    protected static function response_connection($clientGateway){

        $clientGuzz = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false), )); 

        $auth = [];
        if($clientGateway->auth_type == ClientGateway::AUTH_TYPE_BASIC){
            $auth = ['auth' => [$clientGateway->username,$clientGateway->password]];
        } else if($clientGateway->auth_type == ClientGateway::AUTH_TYPE_DIGEST){
            $auth = ['auth' => [$clientGateway->username,$clientGateway->password, $clientGateway->digest]];
        } else if($clientGateway->auth_type == ClientGateway::AUTH_TYPE_FORM){
             $auth  =['form_params' => [
                'username' => $clientGateway->username,
                'password' => $clientGateway->password
            ]];
        } else if($clientGateway->auth_type == ClientGateway::AUTH_TYPE_OAUTH){
            $middleware = new Oauth1([
                'consumer_key'    => $clientGateway->consumer_key,
                'consumer_secret' => $clientGateway->consumer_secret,
                'token'           => $clientGateway->token,
                'token_secret'    => $clientGateway->token_secret
            ]);

            $stack = HandlerStack::create();
            $stack->push($middleware);

            $clientGuzz = new \GuzzleHttp\Client();

            $auth  =['handler' => $stack,'auth'    => 'oauth'];

        } else if($clientGateway->auth_type == ClientGateway::AUTH_TYPE_OAUTH_2){
            $reauth_config = [
                "client_id" => $clientGateway->client_id_for_oauth2,
                "client_secret" => $clientGateway->client_secret_for_oauth2,
                "username" => $clientGateway->username,
                "password" => $clientGateway->password
            ];

            $reauth_client = new \GuzzleHttp\Client([
                'base_uri' => $clientGateway->auth_url_for_oauth2,
            ]);

            $grant_type = new PasswordCredentials($reauth_client, $reauth_config);

            $oauth = new OAuth2Middleware($grant_type);

            Log::info("OAuth token",["result"=>$oauth]);
           
            $stack = HandlerStack::create();
            $stack->push($oauth);

            $clientGuzz = new \GuzzleHttp\Client();

            $auth =['handler' => $stack,'auth' => 'oauth'];

        }
        
        return $clientGuzz->request('GET',$clientGateway->url,$auth);
    }
}
