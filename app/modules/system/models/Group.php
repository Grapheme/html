<?php

class Group extends Eloquent {
	
	protected $guarded = array();

	public static $rules = array(
		'name' => 'required|unique:groups',
		'desc' => 'required|unique:groups',
		'dashboard' => 'required'
	);

	public static $rules_update = array(
		'name' => 'required',
		'desc' => 'required',
		'dashboard' => 'required'
	);

    /**
     * @todo нужно выпилить как пережиток прошлого
     */
	public function roles(){
		return $this->belongsToMany('Role');
	}
    /*
	public function actions(){
		return $this->hasMany('Action', 'id', 'group_id');
	}
    */
    
    ## Количество юзеров в группе
	public function count_users(){
		return User::where('group_id', $this->id)->count();
	}
	
}