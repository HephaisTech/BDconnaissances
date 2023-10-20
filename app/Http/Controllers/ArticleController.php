<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{

    public function index()
    {
        try {
            // Retrieve a list of articles
            $articles = Article::withCount('comments as comment_count')->with('tags')->orderBy('id', 'desc')->get();

            // You can return the list of articles as a JSON response
            return response()->json(['data' => $articles]);
        } catch (\Throwable $th) {
            return $this->jsonResult(false, 'error', $th->getCode(), $th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            // Define custom validation rules
            $rules = [
                'title' => 'required|string|max:255',
                'departement' => 'required|string|max:255',
                'project' => 'required|string|max:255',
                'content' => 'required|string',
            ];

            // Create a validator instance
            $validator = Validator::make($request->all(), $rules);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }

            // Create a new Article instance and fill it with validated data
            $article = new Article();
            $article->title = $request->input('title');
            $article->departement = $request->input('departement');
            $article->project = $request->input('project');
            $article->content = $request->input('content');

            // Set the author_id to the authenticated user's ID (assuming user authentication is implemented)
            $article->author_id = auth()->user()->id;

            // Save the article to the database
            $article->save();

            // Optionally, you can return a response indicating success or the created article
            return response()->json(['message' => 'Article created successfully', 'data' => $article], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'failed', 'errors' => $th->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
            // Retrieve the article by ID
            $article = Article::with('tags', 'comments')->find($id);

            // Check if the article was found
            if (!$article) {
                return response()->json(['message' => 'Article not found'], 404);
            }
            // Return the article as a JSON response
            return response()->json(['data' => $article], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'failed', 'errors' => $th->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        try {
            // Find the article by ID
            $article = Article::find($request->id);

            if (!$article) {
                return response()->json(['message' => 'Article not found'], 404);
            }

            // Validate the incoming request data
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'departement' => 'required|string|max:255',
                'project' => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            // Update the article with the validated data
            $article->update($validatedData);
            return response()->json(['data' => $article]);
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
            // Find the article by ID
            $article = Article::find($request->id);

            // Check if the article exists
            if (!$article) {
                return response()->json(['message' => 'Article not found'], 404);
            }

            // Delete the article
            $article->delete();

            return response()->json(['message' => 'Article deleted successfully']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'failed', 'errors' => $th->getMessage()], 500);
        }
    }
}
