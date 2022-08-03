<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
// use App\Http\Requests\RegisterUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CommentResource as CommentResource;

class CommentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::all();
        return $this->sendResponse(CommentResource::collection($comments) , 'All Comments Sent');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'content' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'string', 'max:255'],
            'user_id' => ['required'],
        ]);

        if($validator->fails())
        {
            return $this->sendError('Please Validate Error' , $validator->errors() );
        }

        $user = Auth::user();
        $input['user_id'] = $user->id;
        $comment = Comment::create($input);

        return $this->sendResponse(new CommentResource($comment), 'Comment Added Successfully' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = Comment::find($id);

        if(is_null($comment))
        {
            return $this->sendError('Comment Not Found');
        }

        return $this->sendResponse(new CommentResource($comment), 'Comment Found Successfully' );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function userComments($user_id)
    {
        $comments = Comment::where('user_id', $user_id)->get();

        if(is_null($comments))
        {
            return $this->sendError('Comment Not Found');
        }

        return $this->sendResponse(new CommentResource($comments), 'Comment Found Successfully' );

    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function postComments($post_id)
    {
        $comments = Comment::where('post_id', $post_id)->get();

        if(is_null($comments))
        {
            return $this->sendError('Comment Not Found');
        }

        return $this->sendResponse(new CommentResource($comments), 'Comment Found Successfully' );

    }

         /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function postCommentReplies($post_id, $id)
    {
        $comments = Comment::where('post_id', $post_id)->where('parent_comment_id', $id)->get();

        if(is_null($comments))
        {
            return $this->sendError('Comment Not Found');
        }

        return $this->sendResponse(new CommentResource($comments), 'Comment Found Successfully' );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment )
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'content' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'string', 'max:255'],
            'user_id' => ['required'],
        ]);

        if($validator->fails())
        {
            return $this->sendError('Please Validate Error' , $validator->errors() );
        }

        if($comment->user_id != Auth::id() )
        {
            return $this->sendError('You Dont Have Rights'  ,$validator->errors() );
        }

        $comment->fill($input)->save();

        return $this->sendResponse(new CommentResource($comment), 'Comment Updated Successfully' );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function like(Comment $comment, $id)
    {
        $comment = Comment::find($id);

        if(is_null($comment))
        {
            return $this->sendError('Comment Not Found');
        }

        ++$comment->likes;
        $comment->save();

        return $this->sendResponse(new CommentResource($comment), 'Comment Liked Successfully' );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function dislike(Comment $comment, $id)
    {
        $comment = Comment::find($id);

        if(is_null($comment))
        {
            return $this->sendError('Comment Not Found');
        }

        ++$comment->dislikes;
        $comment->save();

        return $this->sendResponse(new CommentResource($comment), 'Comment Disliked Successfully' );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $errorMessage = [] ;

        if ( $comment->user_id != Auth::id()) {
            return $this->sendError('You Dont Have Rights' , $errorMessage);
        }

        $comment->delete();
        return $this->sendResponse(new CommentResource($comment), 'Comment Deleted Successfully' );
    }
}
