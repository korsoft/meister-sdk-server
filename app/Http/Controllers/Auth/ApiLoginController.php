<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
use Response;
use \Laravel\Passport\Http\Controllers\AccessTokenController as ATC;
use Log;
use Auth;

class ApiLoginController extends ATC
{


    public function logout(Request $request){

        if($request->user()!=null){
            $request->user()->token()->revoke();

            $json = [
                'success' => true,
                'code' => 200,
                'message' => 'You are Logged out.',
            ];
            return response()->json($json, '200');
        } else {
            return response()->json(["success" => true, "code" => 200, "message" => "You haven't a user session"]);
        }
    }

    public function issueToken(ServerRequestInterface $request)
    {

        Log::info("Request",["r"=>$request]);
        
        try {

            
            //get username (default is :email)
            $username = $request->getParsedBody()['username'];

            //get user
            //change to 'email' if you want
            $user = User::where('email', '=', $username)->with("clients")->first();

            //generate token
            $tokenResponse = parent::issueToken($request);

            //convert response to json string
            $content = $tokenResponse->getContent();

            //convert json to array
            $data = json_decode($content, true);

            if(isset($data["error"]))
                throw new OAuthServerException('The user credentials were incorrect.', 6, 'invalid_credentials', 401);
            
            $data["user_id"] = $user->id;
            $data["user_type"] = $user->type;
            $data["user_email"] = $user->email;

            $clients = $user->clients;

            if(count($clients)>0){

                $d = array();
                foreach ($clients as $c) {
                    $cc = $c;
                    $cc->role=$c->role;
                    $cc->client=$c->client;
                    $d[]=$cc;
                }
                $data["user_clients"]=$d;
            }else
            {
                $data["user_clients"]=null;
            }


            $c = $user->client;

            if($c){
                $cc=$c;
                $cc->client = $c->client;
                $cc->role = $c->role;
                $data["user_default_client"]=$cc;
            }else{
                $data["user_default_client"]=null;
            }

            return Response::json($data);
        }
        catch (ModelNotFoundException $e) { // email notfound
            //return error message
            return response(["message" => "User not found"], 500);
        }
        catch (OAuthServerException $e) { //password not correct..token not granted
            //return error message
            return response(["message" => "The user credentials were incorrect.', 6, 'invalid_credentials"], 500);
        }
        catch (Exception $e) {
            Log::info("Exception",["error"=>$e]);
            return response(["message" => "Internal server error"], 500);
        }
    }
}