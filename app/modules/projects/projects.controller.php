<?php

class ProjectsController extends BaseController {

    public static $name = 'projects_public';
    public static $group = 'projects';

    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        Route::get("projects/dashboard",$class.'@showUserDashboard');
        Route::get("project/{project_slug}",$class.'@showProjectBySlug');
    }
    
    /****************************************************************************/

	public function __construct(){

        View::share('module_name', self::$name);

        $this->tpl = $this->gtpl = static::returnTpl();
        View::share('module_tpl', $this->tpl);
        View::share('module_gtpl', $this->gtpl);
	}
    
    public function showUserDashboard(){

        if(!Allow::enabled_module('projects')):
            return App::abort(404);
        endif;
        if(Auth::guest() || Auth::user()->group_id != 2):
            return App::abort(404);
        endif;
        try{
            if(!$projects = Projects::where('user_id', Auth::user()->id)->with('pages')->with('user')->get()):
                return App::abort(404);
            endif;
            if(View::exists($this->tpl.'dashboard') === FALSE) :
                throw new Exception('Template not found: '.$this->tpl.'dashboard');
            endif;
            return View::make($this->tpl.'dashboard',
                array(
                    'page_title' => Auth::user()->name.' '.Auth::user()->surname,'page_description' => '','page_keywords' => '','page_author' => '',
                    'page_h1' => '',
                    'menu' => NULL,'projects' => $projects
                )
            );
        }catch (Exception $e){
            return App::abort(404);
        }
	}

    public function showProjectBySlug($slug = null){

        if(!Allow::enabled_module('projects')):
            return App::abort(404);
        endif;
        if(Auth::guest()):
            return App::abort(404);
        endif;
        try{
            if(!$project = Projects::where('slug', Request::segment(2))->with('pages')->with('user')->first()):
                return App::abort(404);
            endif;
            if(Auth::user()->group_id == 2):
                if(Auth::user()->id != $project->user_id):
                    return App::abort(404);
                endif;
            endif;
            if(View::exists($this->tpl.'index') === FALSE) :
                throw new Exception('Template not found: '.$this->tpl.'index');
            endif;
            return View::make($this->tpl.'index',
                array(
                    'page_title' => $project->title,'page_description' => '','page_keywords' => '','page_author' => '',
                    'page_h1' => $project->title,
                    'menu' => NULL,'project' => $project
                )
            );
        }catch (Exception $e){
            return App::abort(404);
        }
	}
}