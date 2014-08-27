<?php

class AdminProjectsPagesController extends BaseController {

    public static $name = 'projects_pages';
    public static $group = 'projects';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;

        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
            Route::get("project/edit/{project_id}/pages",array('as'=> self::$name.'_index','uses' => $class.'@index'));
            Route::get("project/edit/{project_id}/pages/create",array('as'=> self::$name.'_create','uses' => $class.'@create'));
            Route::post("project/edit/{project_id}/pages/store",array('as'=> self::$name.'_store','uses' => $class.'@store'));
            Route::get("project/edit/{project_id}/pages/{page_id}/edit",array('as'=> self::$name.'_edit','uses' => $class.'@edit'));
            Route::post("project/edit/{project_id}/pages/{page_id}/update",array('as'=> self::$name.'_update','uses' => $class.'@update'));
            Route::delete("project/edit/{project_id}/pages/{page_id}/delete",array('as'=> self::$name.'_delete','uses' => $class.'@destroy'));
        });
    }

    public static function returnActions() {
        #
    }

    public static function returnInfo() {
        #
    }
    
    /****************************************************************************/

    protected $project;
    protected $project_page;

	public function __construct(Projects $project, ProjectsPages $project_page){

        $this->project = $project->findOrFail(Request::segment(4));
        $this->project_page = $project_page;

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => NULL,
            'tpl'  => static::returnTpl('admin/' . self::$name),
            'gtpl' => static::returnTpl(),
        );
        View::share('module', $this->module);
	}

	public function index(){

        Allow::permission($this->module['group'], 'project_page_view');
        $project = $this->project->with('user')->first();
        $pages = $this->project->pages;
		return View::make($this->module['tpl'].'index',compact('project','pages'));
	}

    /****************************************************************************/

	public function create(){

        Allow::permission($this->module['group'], 'project_create');
        $project = $this->project;
		return View::make($this->module['tpl'].'create',compact('project'));
	}

	public function store(){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'project_create');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE, 'gallery'=>0);
        $validation = Validator::make(Input::all(), ProjectsPages::$rules);
        if($validation->passes()):
            self::saveProjectPagesModel();
            $json_request['responseText'] = "Страница проекта создана";
            $json_request['redirect'] = URL::route('projects_pages_index',array('project_id'=>Input::get('project_id')));
            $json_request['status'] = TRUE;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(),'<br />');
        endif;
        return Response::json($json_request, 200);
	}

    /****************************************************************************/

	public function edit($project_id,$page_id){

        Allow::permission($this->module['group'], 'project_edit');
        $project = $this->project;
        $project_page = $this->project_page->findOrFail($page_id);
		return View::make($this->module['tpl'].'edit', compact('project','project_page'));
	}

	public function update($project_id,$page_id){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'project_edit');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE, 'gallery'=>0);
        $validation = Validator::make(Input::all(), ProjectsPages::$rules);
        if($validation->passes()):
            self::saveProjectPagesModel($this->project_page->find($page_id));
            $json_request['responseText'] = 'Страница проекта обновлена';
            $json_request['redirect'] = URL::route('projects_pages_index',array('project_id'=>Input::get('project_id')));
            $json_request['status'] = TRUE;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(), '<br />');
        endif;
        return Response::json($json_request, 200);
	}

    /****************************************************************************/

	public function destroy($project_id,$page_id){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'project_delete');
        $json_request = array('status'=>FALSE, 'responseText'=>'');
        if(Request::ajax()):
            $this->project_page->find($page_id)->delete();
            $json_request['responseText'] = 'Страница проекта удалена';
            $json_request['status'] = TRUE;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);
	}

    private function saveProjectPagesModel($project_page = NULL){

        if(is_null($project_page)):
            $project_page = $this->project_page;
        endif;

        $project_page->project_id = Input::get('project_id');
        $project_page->title = Input::get('title');
        $project_page->link = Input::get('link');
        $project_page->progress = Input::get('progress');
        $project_page->maket = Input::get('maket');
        $project_page->description = Input::get('description');

        $project_page->save();
        $project_page->touch();

        $this->project_page = $project_page;
        return TRUE;
    }
}
