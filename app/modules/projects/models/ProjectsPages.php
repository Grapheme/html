<?php

class ProjectsPages extends BaseModel {

	protected $guarded = array();
	protected $table = 'project_pages';

	public static $rules = array(
		'project_id' => 'required|min:1',
		'title' => 'required',
	);

    public function projects(){
        return $this->belongsTo('Projects','project_id');
    }
}