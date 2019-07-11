<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Exception;
use Log;
use App\LogRequests;

/**
 * Controller thah handles the Logs of each request.
 */
class RequestLogsController extends Controller
{

    /**
     * A default contructor 
     *
     * 
     */
    public function __construct()
    {
        $this->middleware('checkClientAdmin');

    }

    /**
     * Display all the logs for the request
     * 
     * @param Request $request  Object representation of the request. The vales mus be passed by post, put, delete patch o get trough this object.
     */
    public function index(Request $request)
    {
        ini_set('memory_limit','256M');
        
        $limit = $request->input("limit");
        $page = $request->input("page");
        
        $logs = LogRequests::with("user")->orderBy('created_at','desc');
        
        if($limit){
            $logs->take($limit);
        
            $data =  $logs->paginate($limit) ;

            return $data;
        }else{
            $logs->get();
        }
        
    }

     /**
     * Clears all the logs 
     * 
     * @param Request $request  Object representation of the request. The vales mus be passed by post, put, delete patch o get trough this object.
     */
    public function deleteAll(Request $request){
        LogRequests::truncate();
        return [];
    }

    
}
