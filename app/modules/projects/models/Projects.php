<?php

class Projects extends BaseModel {

	protected $guarded = array();
	protected $table = 'projects';

	public static $rules = array(
		'title' => 'required',
		'user_id' => 'required|min:1',
	);

    public function pages(){
        return $this->hasMany('ProjectsPages','project_id');
    }

    public function user(){
        return $this->belongsTo('User');
    }
}