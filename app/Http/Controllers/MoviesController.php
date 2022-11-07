<?php

namespace App\Http\Controllers;

use App\Models\Movies;
use Illuminate\Http\Request;

class MoviesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Movies::latest()->get();

        return response()->json([
            "type" => "success",
            "data" => $items
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            "name" => ["required", "min:3"],
            "year" => ["nullable", "min:3"],
            "rate" => ["required", "integer","between:1,10"],
        ]);

        // Hash Password
        $formFields["author"] = auth("api")->user()->id;

        // Create User
        $movie = Movies::create($formFields);
        if ($movie) {
            return response()->json([
                "type" => "success",
                "message" => "Movie created succesfully"
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Movies::where("id",$id)->first();

        if ($item) {
            return response()->json([
                "type" => "success",
                "data" => $item
            ], 200);
        } else {
            return response()->json([
                "type" => "error",
                "message" => "Not Found."
            ], 404);
        }
        
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

        $item = Movies::where("id", $id)->first();

        if (empty($item)) {
            return response()->json([
                "type" => "error",
                "message" => "Not Found."
            ], 404); 
        }

        //json_encode($request); exit;

        $formFields = $request->validate([
            "name" => ["required", "min:3"],
            "year" => ["nullable", "min:3"],
            "rate" => ["required", "integer", "beetween:1,10"],
        ]);

        // Hash Password
        $formFields["author"] = auth("api")->user()->id;

        // Create User
        
        $movie = $item->update($formFields);
        if ($movie) {
            return response()->json([
                "type" => "success",
                "message" => "Movie updated succesfully"
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Movies::where("id", $id)->first();
        if (empty($item)) {
            return response()->json([
                "type" => "error",
                "message" => "Not Found."
            ], 404);
        }
        if ($item->author != auth()->id()) {
            return response()->json([
                "type" => "error",
                "message" => "Forbidden.",
            ], 403);
        }

        $item->delete();
        return response()->json([
            "type" => "success",
            "message" => "Movie deleted succesfully"
        ], 200);
    }
}
