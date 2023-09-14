<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $commants = Comment::orderBy('id', 'desc')->get();
            if (!$commants) {
                return response()->json(['message' => 'commants not found'], 404);
            }
            return response()->json(['data' => $commants], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'failed', 'errors' => $th->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'article_id' => 'required|exists:articles,id',
                'author_id' => 'required|exists:users,id',
                'content' => 'required|string',
                'withfile' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Define validation rules for the image file
            ]);

            // Handle image file upload
            if ($request->hasFile('withfile')) {
                $file = $request->file('withfile');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('uploads', $fileName); // Store the uploaded file in the 'uploads' directory
                $validatedData['withfile'] = $fileName; // Save the file name in the database
            }

            // Create a new comment
            $comment = Comment::create($validatedData);

            // Return a JSON response indicating success
            return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 201);
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
            // Retrieve the Comment by ID
            $Comment = Comment::with('author')->find($id);

            // Check if the Comment was found
            if (!$Comment) {
                return response()->json(['message' => 'Comment not found'], 404);
            }
            // Return the Comment as a JSON response
            return response()->json(['data' => $Comment], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'failed', 'errors' => $th->getMessage()], 500);
        }
    }
    /**
     * Récupère les commentaires d'un article spécifique.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCommentsByArticle($id)
    {
        try {
            $comments = Comment::where('article_id', $id)->orderBy('created_at', 'desc')->get();
            return response()->json(['data' => $comments], 200);
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
            $comment = Comment::find($request->id);
            if (!$comment) {
                return response()->json(['message' => 'Comment not found'], 404);
            }

            // Validate the incoming request data
            $validatedData = $request->validate([
                'article_id' => 'required|exists:articles,id',
                'author_id' => 'required|exists:users,id',
                'content' => 'required|string',
                'withfile' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Define validation rules for the image file
            ]);
            // Handle image file upload if provided in the request
            if ($request->hasFile('withfile')) {
                // Delete the old file if it exists
                if ($comment->withfile) {
                    Storage::delete('uploads/' . $comment->withfile);
                }

                $file = $request->file('withfile');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('uploads', $fileName); // Store the uploaded file in the 'uploads' directory
                $validatedData['withfile'] = $fileName; // Save the file name in the database
            }

            // Update the comment with the validated data
            $comment->update($validatedData);

            // Return a JSON response indicating success
            return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment], 200);
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
            // Find the Comment by ID
            $Comment = Comment::find($request->id);

            // Check if the tag exists
            if (!$Comment) {
                return response()->json(['message' => 'Comment not found'], 404);
            }

            // Delete the Comment
            $Comment->delete();

            return response()->json(['message' => 'Comment deleted successfully']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'failed', 'errors' => $th->getMessage()], 500);
        }
    }

    public function upvote(Request $request)
    {
        try {
            $comment = Comment::findOrFail($request->id);

            $user = auth()->user();

            // Check if the user has already upvoted the comment
            if ($comment->upvoters->contains($user)) {
                // User has already upvoted, so remove the upvote
                $comment->upvoters()->detach($user);
                $comment->decrement('upvotes');
                $message = 'Comment upvote removed.';
            } else {
                // User hasn't upvoted, so add an upvote
                $comment->upvoters()->attach($user);
                $comment->increment('upvotes');
                $message = 'Comment upvoted.';
            }

            return response()->json(['message' => $message, 'upvotes' => $comment->upvotes]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'failed', 'errors' => $th->getMessage()], 500);
        }
    }
}
