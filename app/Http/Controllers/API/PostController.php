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
use App\Http\Resources\PostResource as PostResource;


class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return $this->sendResponse(PostResource::collection($posts) , 'All Posts Sent');
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
            'caption' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'user_id' => ['required'],
        ]);

        if($validator->fails())
        {
            return $this->sendError('Please Validate Error'  ,$validator->errors() );
        }

        $user = Auth::user();
        $input['user_id'] = $user->id;
        $post = Post::create( $input);

        return $this->sendResponse(new PostResource($post), 'Post Added Successfully' );

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = Post::find($slug);

        if(is_null($post))
        {
            return $this->sendError('Post Not Found');
        }

        return $this->sendResponse(new PostResource($post), 'Post Found Successfully' );

    }

        /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function userPosts($user_id)
    {
        $posts = Post::where('user_id', $user_id)->get();

        if(is_null($posts))
        {
            return $this->sendError('Post Not Found');
        }

        return $this->sendResponse(new PostResource($posts), 'Post Found Successfully' );

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'caption' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'string', 'max:255'],
        ]);

        if($validator->fails())
        {
            return $this->sendError('Please Validate Error'  ,$validator->errors() );
        }

        if($post->user_id != Auth::id() )
        {
            return $this->sendError('You Dont Have Rights'  ,$validator->errors() );
        }

        $post->fill($input)->save();

        return $this->sendResponse(new PostResource($post), 'Post Updated Successfully' );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function like(Post $post, $id)
    {
        $post = Post::find($id);

        if(is_null($post))
        {
            return $this->sendError('Post Not Found');
        }

        ++$post->likes;
        $post->save();

        return $this->sendResponse(new PostResource($post), 'Post Liked Successfully' );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function dislike(Post $post, $id)
    {
        $post = Post::find($id);

        if(is_null($post))
        {
            return $this->sendError('Post Not Found');
        }

        ++$post->dislikes;
        $post->save();

        return $this->sendResponse(new PostResource($post), 'Post Disliked Successfully' );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $errorMessage = [] ;

        if ( $post->user_id != Auth::id()) {
            return $this->sendError('You Dont Have Rights' , $errorMessage);
        }

        $post->delete();
        return $this->sendResponse(new PostResource($post), 'Post Deleted Successfully' );
    }
}
