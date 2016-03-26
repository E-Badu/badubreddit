<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	/**
	* Get the user that owns the comment
	*/
    public function user()
    {
    	return $this->belongsTo('App\User');
    }

	/**
	* Get the post that owns the comment
	*/

    public function post()
    {
    	return $this->belongsTo('App\Post');
    }

	/**
	* Get the comment that owns the comment
	*/
    public function parentComment()
    {
    	return $his->belongsTo('App\Comment');
    }

	/**
	* Get the child comments owned by the comment
	*/
    public function childComments()
    {
    	return $his->hasMany('App\Comment');
    }
}
