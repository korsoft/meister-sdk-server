<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Exception;
use Log;
use App\LogRequests;

/**
 * @group Request's Logs
 *
 * APIs for retreiving logs for the requests
 */
class RequestLogsController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkClientAdmin');

    }

    /**
     * Display all the logs for the request
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
     */
    public function deleteAll(Request $request){
        LogRequests::truncate();
        return [];
    }

    
}
