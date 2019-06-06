<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Exception;
use Log;
use App\LogRequests;


class RequestLogsController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkClientAdmin');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        ini_set('memory_limit','256M');
        
        $limit = $request->input("limit");
        $page = $request->input("page");
        
        $logs = LogRequests::with("user")->orderBy('created_at','desc');
        
        if($limit){
            $logs->take($limit);
        
            return $logs->paginate($limit) ;
        }else{
            $logs->get();
        }
        
    }

    public function deleteAll(Request $request){
        LogRequests::truncate();
        return [];
    }

    
}
