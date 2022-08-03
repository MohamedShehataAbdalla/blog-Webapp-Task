<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\CommentController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:api')->group( function(){

    Route::resource('posts', PostController::class);
    Route::post('post/like/{id}', [PostController::class, 'like']);
    Route::post('post/dislike/{id}', [PostController::class, 'dislike']);
    Route::get('posts/user/{user_id}', [PostController::class, 'userPosts']);


    Route::resource('comments', CommentController::class);
    Route::post('comment/like/{id}', [CommentController::class, 'like']);
    Route::post('comment/dislike/{id}', [CommentController::class, 'dislike']);
    Route::get('comments/user/{user_id}', [CommentController::class, 'userComments']);
    Route::get('posts/{post_id}/comments', [CommentController::class, 'postComments']);
    Route::get('posts/{post_id}/comments/{id}', [CommentController::class, 'postCommentReplies']);

});
