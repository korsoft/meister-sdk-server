<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ClientGateway;
use App\User;
use App\ClientGatewayRelation;

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
            return ClientGateway::get();
        } else {
            $clientsIds= $user->clients()->pluck('client_id')->toArray();  
            Log::info("ClientsIds",$clientsIds);
            $cgrs = ClientGateway::whereHas('clients', function ($query)  use ($clientsIds){
                $query->whereIn('client_id', $clientsIds);
            })->get(); 
            return $cgrs;
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

        $clientGateway->save();

        if($userInSession->type != User::TYPE_SYSTEM_ADMIN){
            $cgr = new ClientGatewayRelation;
            $cgr->client_gateway_id = $clientGateway->id;
            $cgr->client_id = $userInSession->client()->first()->id;
            $cgr->save();
        } 
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
                $clientGateway->client_id == $request->user()->client->client_id)
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

        if($user->type == User::TYPE_SYSTEM_INTEGRATOR && $user->client_id != $clientGateway->client_id)
            throw new Exception("You can't delete the gateway from another client", 1);

        $clientGateway->delete();

        return $clientGateway;
    }

    public function test_connection(Request $request, $id){
        $clientGateway = ClientGateway::find($id);

        if(!$clientGateway)
            throw new Exception("Error the gateway doesn't exist", 1);

        try {
            
            $response = self::response_connection($clientGateway, $request);

           if($response["response"]->getStatusCode()!="200")
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

    public function execute(Request $request, $id){
        $clientGateway = ClientGateway::find($id);

        if(!$clientGateway)
            throw new Exception("Error the gateway doesn't exist", 1);

        try {
            
            $response = self::response_connection($clientGateway, $request);

           if($response["response"]->getStatusCode()!="200")
               throw new Exception("Connection failure", 1);

            $body = (string) $response["response"]->getBody();

            $result = json_decode($body, true);

            //Log::info("Result",["response" => $result]);

            if(is_array($result) && count($result)>0){
                if(isset($result["d"]) && isset($result["d"]["results"]) && isset($result["d"]["results"][0]) ){
                    $report = $result["d"]["results"][0];
                    if(isset($report["Json"])){
                        //Log::info("Report json",["result"=>$report["Json"]]);
                        if(self::isJson($report["Json"])){
                            return json_decode($report["Json"], true);
                        } else {
                            return response()->json(array(
                                'code'      =>  404,
                                'message'   =>  "Invalid JSON Response"
                            ), 404);
                        }
                        
                    }
                }
            }

            
        } catch(\GuzzleHttp\Exception\ClientException $e){
            Log::info("ClientException",["result" => $e]);
            throw new Exception("Connection failure", 1);
        } catch(Exception $e){
            Log::info("Exception",["result" => $e]);
            throw new Exception("Connection failure", 1);
        }
    }

    public function execute_changes(Request $request, $id){
        
        $request->validate([
            'json' => 'required'
        ]);

        $json = $request->input("json");

        Log::info("execute_changes",["json"=>$json]);
        
        $clientGateway = ClientGateway::find($id);

        if(!$clientGateway)
            throw new Exception("Error the gateway doesn't exist", 1);

        try {
            
            $response = self::response_connection($clientGateway, $request);

           if($response["response"]->getStatusCode()!="200")
               throw new Exception("Connection failure", 1);

            $body = (string) $response["response"]->getBody();

            return json_decode($body, true);

        } catch(\GuzzleHttp\Exception\ClientException $e){
            Log::info("ClientException",["result" => $e]);
            throw new Exception("Connection failure", 1);
        } catch(Exception $e){
            Log::info("Exception",["result" => $e]);
            throw new Exception("Connection failure", 1);
        }
    }

    public function execute_endpoint(Request $request, $id){
        $request->validate([
            'endpoint' => 'required'
        ]);

        $endpoint = $request->input("endpoint");

         Log::info("execute_endpoint",["endpoint"=>$endpoint]);
        
        $clientGateway = ClientGateway::find($id);

        if(!$clientGateway)
            throw new Exception("Error the gateway doesn't exist", 1);

        try {
            
            $compression = $request->input('compression');

            $response = self::response_connection($clientGateway, $request);

           if($response["response"]->getStatusCode()!="200")
               throw new Exception("Connection failure", 1);

            $body = (string) $response["response"]->getBody();

            $result = json_decode($body, true);

            Log::info("Result",["response" => $result]);

            if(is_array($result) && count($result)>0){
                if(isset($result["d"]) && isset($result["d"]["results"]) && isset($result["d"]["results"][0]) ){
                    $report = $result["d"]["results"][0];
                    if($compression === 'O'){
                        return [
                            "url" => $response["url"],
                            "data" => self::StringToByteArray($report["Json"]),
                            "compression" => "O"
                        ]; 
                    }
                    if(isset($report["Json"])){
                        $json = str_replace("\\n", '', $report["Json"]);
                        //$json =  stripslashes($json);
                        Log::info("Result in execute_endpoint (json): " .$json);
                        if(self::isJson($json)){
                            return [
                                "url" => $response["url"],
                                "data" => json_decode($json, true)
                            ]; 
                        } else {
                            return response()->json(array(
                                'code'      =>  404,
                                'message'   =>  "Invalid JSON Response"
                            ), 404);
                        }
                        
                    }
                }
            }

            return [
                "url" => $response["url"],
                "data" => []
            ];

        } catch(\GuzzleHttp\Exception\ClientException $e){
            Log::info("ClientException",["result" => $e]);
            throw new Exception("Connection failure", 1);
        } catch(Exception $e){
            Log::info("Exception",["result" => $e]);
            throw new Exception("Connection failure", 1);
        }

    }



    protected static function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }


    protected static function StringToByteArray($st){
        $h = str_replace(array("\\", "\\r","\\n"), '', $st);
        Log::info($st);
        $NumberChars = strlen($h);
        $bytes = [];
        $cont = 0;
        for($i=0; $i < $NumberChars; $i+=2){
            $newChars = $h[$i].$h[$i+1];
            $bytes[$cont++] = hexdec($newChars);

        }
        return $bytes;   
    }

    protected static function response_connection($clientGateway, $request){

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

        $auth["query"] = self::getQueryParamsForGateway($clientGateway, $request);

        Log::info("Auth",$auth);
        
        $c =  $clientGuzz->request('GET',$clientGateway->url . ClientGateway::URL_GENERIC_PATH,$auth);

        $url = $clientGateway->url . ClientGateway::URL_GENERIC_PATH . "?" . self::build_http_query($auth["query"]);

        Log::info("url: " . $url);

        return [
            "url" => $url,
            "response" => $c
        ];
    }

    protected static function build_http_query( $query ){

        $query_array = array();
        foreach( $query as $key => $key_value ){
            $query_array[] = $key . '=' . $key_value; //urlencode( $key ) . '=' . urlencode( $key_value );
        }
        return implode( '&', $query_array );
    }



    protected static function  getQueryParamsForGateway($clientGateway, $request){
        

        $endpoint = $request->input("endpoint");
        $json = $request->input("json");
        $compression = $request->input("compression");
        $ADDITIONAL_PARAMS = "";
        
        $params = "";

        if($json != null){
            $json = str_replace("\\n", '', $json);
        }

        if($request->input("style")!=null){
            $ADDITIONAL_PARAMS .=",\"STYLE\":\"".$request->input("style")."\""; 
        }
        if($request->input("SDK_HINT")!=null){
            $ADDITIONAL_PARAMS .=",\"SDK_HINT\":\"".$request->input("SDK_HINT")."\"" ;
        }
        if($request->input("Asynch")!=null){
            $ADDITIONAL_PARAMS .=",\"Asynch\":\"".$request->input("Asynch")."\"" ;
        }
        if($request->input("Queued")!=null){
            $ADDITIONAL_PARAMS .=",\"Queued\":\"".$request->input("Queued")."\"" ;
        }
        if($request->input("BPM")!=null){
            $ADDITIONAL_PARAMS .=",\"BPM\":\"".$request->input("BPM")."\"" ;
        }
        if($request->input("Callback")!=null){
            $ADDITIONAL_PARAMS .=",\"Callback\":\"".$request->input("Callback")."\"" ;
        }
        if($request->input("Test_Run")!=null){
            $ADDITIONAL_PARAMS .=",\"Test_Run\":\"".$request->input("Test_Run")."\"" ;
        }

        if($json == null && $endpoint == null){
            $params = "[{\"COMPRESSION\":\"\"".$ADDITIONAL_PARAMS."}]";
            $query = [
                "Endpoint" => "'" . ClientGateway::ENDPOINT_LOOKUP . "'",
                "Parms" => "'".$params."'",
                "Json" => "'{\"TYPE\":\"C\"}'",
                "\$format" => "json"
            ];
        } else if($json != null && $endpoint == null){
            $params = "[{\"COMPRESSION\":\"\"".$ADDITIONAL_PARAMS."}]";
            $query = [
                "Endpoint" => "'" . ClientGateway::ENDPOINT_MANAGER . "'",
                "Parms" => "'".$params."'",
                "Json" => "'".$json."'",
                "\$format" => "json"
            ];
        } else if($endpoint != null && $json == null){
            $params = "[{\"METADATA\":\"X\"".$ADDITIONAL_PARAMS."}]";
            $query = [
                "Endpoint" => "'" . $endpoint . "'",
                "Parms" => "'".$params."'",
                "Json" => "''",
                "\$format" => "json"
            ];
        } else if($endpoint != null && $json != null){
            if($compression=="I"){
                $json= self::compress($json);
            }

            $params = "[{\"COMPRESSION\":\"" . ($compression!=null ? $compression : "") . "\"".$ADDITIONAL_PARAMS."}]";
                $query = [
                    "Endpoint" => "'" . $endpoint . "'",
                    "Parms" => "'".$params."'",
                    "Json" => "'".$json."'",
                    "\$format" => "json"
                ];
             
        }
        $client_number = $request->input("client_number");
        if($client_number!=null){
            $query["sap_client"] = $client_number;
        }
        return $query;
    }

    public static function compress($string){
        $stringCompress = gzencode($string);
        $stringhex="";
        for ($i=0; $i<strlen($stringCompress); $i++){
            $ord = ord($stringCompress[$i]);
            $hexCode = dechex($ord);
            $stringhex .= substr('0'.$hexCode, -2);
        }

        return $stringhex;
    }

}
