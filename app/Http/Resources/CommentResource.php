<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id, 
            'user_id' => $this->user_id, 
            'post_id' => $this->post_id, 
            'content' => $this->content, 
            'photo' => $this->photo, 
            'likes' => $this->likes, 
            'dislikes' => $this->dislikes, 
            'parent_comment_id' => $this->parent_comment_id, 
            'deleted_at' => $this->deleted_at, 
            'created_at' => $this->created_at, 
            'updated_at' => $this->updated_at,
        ];
         
    }
}
