<?php

class AdminProjectsController extends BaseController {

    public static $name = 'projects';
    public static $group = 'projects';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
        	Route::controller($class::$group, $class);
        });
    }

    public static function returnExtFormElements() {
        #
    }

    public static function returnActions() {
        return array(
            'project_view'   => 'Просмотр',
            'project_create' => 'Создание',
            'project_edit'   => 'Редактирование',
            'project_delete' => 'Удаление',
        );
    }

    public static function returnInfo() {
        return array(
            'name' => self::$name,
            'group' => self::$group,
            'title' => 'Проекты',
            'visible' => 1,
        );
    }

    public static function returnMenu() {
        return array(
            array(
                'title' => 'Проекты',
                'link' => self::$group,
                'class' => 'fa-copy',
                'permit' => 'view',
            ),
        );
    }

    /****************************************************************************/

    protected $project;

	public function __construct(Projects $project){

        $this->project = $project;
        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl'  => static::returnTpl('admin/' . self::$name),
            'gtpl' => static::returnTpl(),
        );
        View::share('module', $this->module);
	}

	public function getIndex(){

        Allow::permission($this->module['group'], 'project_view');
		return View::make($this->module['tpl'].'index', array('projects'=>$this->project->with('pages')->with('user')->get()));
	}

    /****************************************************************************/

	public function getCreate(){

        Allow::permission($this->module['group'], 'project_create');
		return View::make($this->module['tpl'].'create');
	}

	public function postStore(){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'project_create');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE, 'gallery'=>0);
        $validation = Validator::make(Input::all(), Projects::$rules);
        if($validation->passes()):
            self::saveProjectModel();
            $json_request['responseText'] = "Проект создан";
            $json_request['redirect'] = link::auth($this->module['rest']);
            $json_request['status'] = TRUE;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(),'<br />');
        endif;
        return Response::json($json_request, 200);
	}

    /****************************************************************************/

	public function getEdit($id){

        Allow::permission($this->module['group'], 'project_edit');
        $project = $this->project->where('id',$id)->with('user')->first();
        return View::make($this->module['tpl'].'edit', compact('project'));
	}

	public function postUpdate($id){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'channel_edit');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE, 'gallery'=>0);
        $validation = Validator::make(Input::all(), Projects::$rules);
        if($validation->passes()):
            self::saveProjectModel($this->project->find($id));
            $json_request['responseText'] = 'Проект обновлен';
            $json_request['redirect'] = link::auth($this->module['rest']);
            $json_request['status'] = TRUE;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(), '<br />');
        endif;
        return Response::json($json_request, 200);
	}

    /****************************************************************************/

    public function deleteDestroy($id){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'project_delete');
        $json_request = array('status'=>FALSE, 'responseText'=>'');
        if(Request::ajax()):
            $this->project->find($id)->pages()->delete();
            $this->project->find($id)->delete();
            $json_request['responseText'] = 'Проект удален';
            $json_request['status'] = TRUE;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);
    }

    private function saveProjectModel($project = NULL){

        if(is_null($project)):
            $project = $this->project;
        endif;

        $project->user_id = Input::get('user_id');
        $project->slug = BaseController::stringTranslite(Input::get('title'));
        $project->title = Input::get('title');
        $project->description = Input::get('description');
        $project->arhive = Input::get('arhive') ? Input::get('arhive') : 0;

        $project->save();
        $project->touch();

        $this->project = $project;
        return TRUE;
    }
}
