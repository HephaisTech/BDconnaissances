<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $tags = Tag::all();
            return response()->json(['data' => $tags]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'failed', 'errors' => $th->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:tags|max:255',
            ]);

            $tag = Tag::create([
                'name' => $request->input('name'),
            ]);

            return response()->json(['message' => 'Tag created successfully', 'data' => $tag], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'failed', 'errors' => $th->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return $this->create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            // Retrieve the tag by ID
            $tag = Tag::find($id);

            // Check if the tag was found
            if (!$tag) {
                return response()->json(['message' => 'tag not found'], 404);
            }
            // Return the tag as a JSON response
            return response()->json(['data' => $tag], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'failed', 'errors' => $th->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        try {
            // Find the tag by ID
            $tag = Tag::find($request->id);

            if (!$tag) {
                return response()->json(['message' => 'tag not found'], 404);
            }

            // Validate the incoming request data
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'departement' => 'required|string|max:255',
                'project' => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            // Update the tag with the validated data
            $tag->update($validatedData);
            return response()->json(['data' => $tag]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'failed', 'errors' => $th->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        return $this->edit($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            // Find the tag by ID
            $tag = Tag::find($request->id);

            // Check if the tag exists
            if (!$tag) {
                return response()->json(['message' => 'tag not found'], 404);
            }

            // Delete the tag
            $tag->delete();

            return response()->json(['message' => 'tag deleted successfully']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'failed', 'errors' => $th->getMessage()], 500);
        }
    }
}
