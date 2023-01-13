<?php
namespace App\Trait;

trait Httpresponses{

public function Success($data,$message=null,$code=200)
{
    return response()->json([
        'status'=>"created succesfully",
        'message'=>$message,
         'data'=>$data
    ],$code);
}
public function error($data,$message=null,$code)
{
    return response()->json([
        'status'=>"error has occurred",
        'message'=>$message,
        'data'=>$data
    ],$code);
}
}
