<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TagController;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/articles', [ArticleController::class, 'index']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

//
Route::get('/confirmUser/{id}', function (Request $request) {
    return response()->json(['success' => $request->id]);
});



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum', 'ability:create']);
});




Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::group(['prefix' => 'articles'], function () {
        // Routes for the ArticleController

        // Example route for getting all articles
        Route::get('/', [ArticleController::class, 'index']);

        // Example route for creating a new article
        Route::post('/', [ArticleController::class, 'store']);

        // Example route for getting a specific article
        Route::get('{id}', [ArticleController::class, 'show']);

        // Example route for updating an article
        Route::put('/', [ArticleController::class, 'update']);

        // Example route for deleting an article
        Route::delete('/', [ArticleController::class, 'destroy']);
    });

    Route::group(['prefix' => 'tags'], function () {
        // Routes for the tags

        // Example route for getting all tags
        Route::get('/', [TagController::class, 'index']);

        // Example route for creating a new article
        Route::post('/', [TagController::class, 'store']);

        // Example route for getting a specific article
        Route::get('{id}', [TagController::class, 'show']);

        // Example route for updating an article
        Route::put('/', [TagController::class, 'update']);

        // Example route for deleting an article
        Route::delete('/', [TagController::class, 'destroy']);
    });

    Route::group(['prefix' => 'comments'], function () {
        // Routes for the comments

        // Example route for getting all tags
        Route::get('/', [CommentController::class, 'index']);

        // Example route for creating a new comments
        Route::post('/', [CommentController::class, 'store']);

        // Example route for getting a specific comments
        Route::get('{id}', [CommentController::class, 'show']);

        Route::get('/article/{id}', [CommentController::class, 'getCommentsByArticle']);

        // Example route for updating an comments
        Route::put('/', [CommentController::class, 'update']);

        // Example route for deleting an comments
        Route::delete('/', [CommentController::class, 'destroy']);
    });
});
