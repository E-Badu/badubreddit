<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
	* Get the user that owns the post
	*/
    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    /**
	* Get the subbreddit that owns the psot
	*/

    public function post()
    {
    	return $this->belongsTo('App\Subbreddit');
    }


	/**
	* Get the comments owned by the post
	*/
    public function comments()
    {
    	return $his->hasMany('App\Comment');
    }
}
