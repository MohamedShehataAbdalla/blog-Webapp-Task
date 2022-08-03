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

        // $post->caption = $input['caption'];
        // $post->content = $input['content'];
        // $post->photo = $input['photo'];

        $post->fill($input)->save();

        return $this->sendResponse(new PostResource($post), 'Post Updated Successfully' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return $this->sendResponse(new PostResource($post), 'Post Deleted Successfully' );
    }
}
