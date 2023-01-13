<?php

namespace App\Http\Controllers;

use App\Http\Requests\storepostrequest;
use App\Models\Post as ModelsPost;
use Illuminate\Http\Request;

class Post extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Post=ModelsPost::all();
         return response()->json([
            'status'=>true,
            'posts'=>$Post
         ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storepostrequest $request)
    {
         $posts=ModelsPost::create($request->all());
           return response()->json([
            "status"=>true,
            "message"=>"post created succesfully",
            "post"=>$posts
           ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,ModelsPost $Post)
    {
        $Post->update($request->all());
        return response()->json([
            "status"=>true,
            "message"=>"post updated succesfully",
            "posts"=>$Post

        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModelsPost $Post)
    {
        $Post->delete();
        return response()->json([
            "status"=>true,
            "message"=>"post delete succesfully"
        ],200);
    }
}
