<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message
        ];
        return response()->json($response , 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessage=[], $code = 404)
    {
        $response = [
            'success' => false,
            'data' => $error,
        ];
        if(!empty($errorMessage))
        {
            $response['data'] = $errorMessage;
        }
        return response()->json($response , $code);
    }

}
