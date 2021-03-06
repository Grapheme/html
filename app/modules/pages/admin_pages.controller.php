<?php

class AdminPagesController extends BaseController {

    public static $name = 'pages';
    public static $group = 'pages';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
        	Route::controller($class::$group, $class);
        });
    }

    ## Shortcodes of module
    public static function returnShortCodes() {
    }

    ## Actions of module (for distribution rights of users)
    ## return false;   # for loading default actions from config
    ## return array(); # no rules will be loaded
    public static function returnActions() {
        return array(
        	'view'   => 'Просмотр',
        	'create' => 'Создание',
        	'edit'   => 'Редактирование',
        	'delete' => 'Удаление',
        );
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
        return array(
        	'name' => self::$name,
        	'group' => self::$group,
        	'title' => 'Страницы',
            'visible' => 1,
        );
    }

    ## Menu elements of the module
    public static function returnMenu() {
        return array(
            array(
            	'title' => 'Страницы',
                'link' => self::$group,
                'class' => 'fa-list-alt', 
                'permit' => 'view',
            ),
        );
    }

    /****************************************************************************/

	public function __construct(I18nPage $page, I18nPageMeta $page_meta){

		$this->page = $page;
		$this->page_meta = $page_meta;
        $this->locales = Config::get('app.locales');

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin'),
            'gtpl' => static::returnTpl(),
        );
        View::share('module', $this->module);
	}

	public function getIndex(){

        Allow::permission($this->module['name'], 'view');
        $pages = $this->page->orderBy('start_page', 'DESC')->with(array('metas'=>function($query){$query->where('language',Config::get('app.locale'));}))->get();
		return View::make($this->module['tpl'].'index',array('pages' => $pages, 'locales' => $this->locales));
	}

	public function getCreate(){

		$this->moduleActionPermission('pages','create');
		return View::make($this->module['tpl'].'create',array('templates'=>$this->templates(__DIR__), 'locales' => $this->locales));
	}

	public function postStore(){

		$this->moduleActionPermission('pages','create');
		$json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
		if(Request::ajax()):
			$validator = Validator::make(Input::all(), I18nPage::$rules);
			if($validator->passes()):
				self::savePageModel();
				$json_request['responseText'] = 'Страница создана';
				$json_request['redirect'] = link::auth('pages');
				$json_request['status'] = TRUE;
			else:
				$json_request['responseText'] = 'Неверно заполнены поля';
				$json_request['responseErrorText'] = $validator->messages()->all();
			endif;
		else:
			return App::abort(404);
		endif;
		return Response::json($json_request,200);
	}

	public function getEdit($id){

		$this->moduleActionPermission('pages', 'edit');
		$page = $this->page->find($id);
		if(is_null($page))
			return App::abort(404);
        #print_r($page);

        $metas = $this->page_meta->where('page_id', $page->id)->get();
		if(is_null($metas))
			return App::abort(404);

        foreach ($metas as $m => $meta) {
        	$page_meta[$meta->language] = $meta;
        }
        #print_r($page_meta);

		return View::make($this->module['tpl'].'edit',array('page'=>$page, 'page_meta'=>$page_meta, 'templates'=>$this->templates(__DIR__), 'locales' => $this->locales));

	}

	public function postUpdate($id){

		$this->moduleActionPermission('pages','edit');
		$json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
		if(Request::ajax()):
			$validator = Validator::make(Input::all(), I18nPage::$rules);
			if($validator->passes()):
				$page = $this->page->find($id);
				self::savePageModel($page);
				$json_request['responseText'] = 'Страница сохранена';
				#$json_request['redirect'] = link::auth('pages');
				$json_request['status'] = TRUE;
			else:
				$json_request['responseText'] = 'Неверно заполнены поля';
				$json_request['responseErrorText'] = $validator->messages()->all();
			endif;
		else:
			return App::abort(404);
		endif;
		return Response::json($json_request,200);

	}

	public function deleteDestroy($id){

		$this->moduleActionPermission('pages','delete');
		$json_request = array('status'=>FALSE,'responseText'=>'');
		if(Request::ajax()):
            ## Следующая строка почему-то не работает:
		    #$b = $this->page_meta->where('page_id', $id)->delete;
		    ## Ну да ладно, удалим все языковые версии страницы вот так:
		    $metas = $this->page_meta->where('page_id', $id)->get();
		    foreach ($metas as $meta)
		        @$meta->delete();
		    ## Удаляем саму страницу
		    $a = @$this->page->find($id)->delete();
            ## Возвращаем сообщение чт овсе ОК
			#if( $a && $b ):
				$json_request['responseText'] = 'Страница удалена';
				$json_request['status'] = TRUE;
			#endif;
		else:
			return App::abort(404);
		endif;
		return Response::json($json_request,200);
	}

	public function getMenu(){

		$bread = "Menu";
		$pages = $this->page->all();
		$pages = $pages->sortBy('sort_menu');
		return View::make($this->module['tpl'].'menu', compact('bread', 'pages'));
	}

	public function postSort(){

		$sorts = json_decode(Input::get('menu'));
		foreach($sorts as $key => $sort):
			//print_r(Page::find($sort)->get());/*->update(array('sort_menu' => $key));*/
			$model = Page::find($sort);
			$model->sort_menu = $key;
			$model->save();
		endforeach;
	}

	private function savePageModel($page = NULL){

		if(is_null($page)):
			$page = $this->page;
		endif;

        ## Собираем значения объекта для страницы
		if(Allow::enabled_module('templates') || 1):
			$page->template = Input::get('template');
		else:
			$page->template = 'default';
		endif;
		$page->publication = 1;
        $page->in_menu = (is_null(Input::get('in_menu')))?0:Input::get('in_menu');
		$page->sort_menu = (int)$this->page->max('sort_menu')+1;
		if($page::count() == 0 || $page->start_page == 1) {
			$page->start_page = 1;
			$slug = ''; ## Пустой URL у главной страницы
		} else {
    		$slug = Input::get('slug');
    		if (!$slug) {
    		    foreach($this->locales as $locale) {
        			if(Input::get('name.' . $locale) != '') {
        				$slug = $this->stringTranslite(Input::get('name.' . $locale));
        				if ($slug != '')
        				    break;
        	        }
    		    }
    		}
		}
		$page->slug = $slug;
		## Сохраняем страницу
        $page->save();
		$page->touch();

        ## ID страницы (только что созданной, или сохраненной ранее)
        $page_id = $page->id;
        #$page_id = 111; ## Для дебага

		#return "<pre>" . print_r($page, 1) . "</pre>";

        ## Перебираем все локали и создаем объекты page_meta для каждой языковой версии страницы
		foreach($this->locales as $locale) {
    		#$page->name = (Input::get('name.' . $locale));

            ## Ищем нужную языковую версию текущей новости
            $page_meta = I18nPageMeta::where('page_id', $page_id)->where('language', $locale)->first();
            ## Если ее нет - создаем новую
            if (!isset($page_meta) || !$page_meta->id)
                $page_meta = new I18nPageMeta;
            ## Заполняем свойства новости переданными данными
            $page_meta->page_id = $page_id;
            $page_meta->language = $locale;
            $page_meta->name = Input::get('name.' . $locale);
            $page_meta->content = Input::get('content.' . $locale);
            /*
            $page_meta->seo_url = Input::get('seo_url.' . $locale);
            $page_meta->seo_title = Input::get('seo_title.' . $locale);
            $page_meta->seo_description = Input::get('seo_description.' . $locale);
            $page_meta->seo_keywords = Input::get('seo_keywords.' . $locale);
            $page_meta->seo_h1 = Input::get('seo_h1.' . $locale);
            */
    		if(Allow::enabled_module('seo')):
    			if(is_null(Input::get('seo_url.' . $locale))):
    				$page_meta->seo_url = '';
    			elseif(Input::get('seo_url.' . $locale) === ''):
    				$page_meta->seo_url = $this->stringTranslite(Input::get('name.' . $locale));
    			else:
    				$page_meta->seo_url = $this->stringTranslite(Input::get('seo_url.' . $locale));
    			endif;
    			$page_meta->seo_url = (string)$page_meta->seo_url;
    			if(Input::get('seo_title.' . $locale) == ''):
    				$page_meta->seo_title = $page_meta->name;
    			else:
    				$page_meta->seo_title = trim(Input::get('seo_title.' . $locale));
    			endif;
    			$page_meta->seo_description = Input::get('seo_description.' . $locale);
    			$page_meta->seo_keywords = Input::get('seo_keywords.' . $locale);
    			$page_meta->seo_h1 = Input::get('seo_h1.' . $locale);
    		else:
    			$page_meta->seo_url = $this->stringTranslite(Input::get('name.' . $locale));
    			$page_meta->seo_title = Input::get('name.' . $locale);
    			$page_meta->seo_description = $page_meta->seo_keywords = $page_meta->seo_h1 = '';
    		endif;

    		## Дебаг перед сохранением
    		#return "<pre>" . print_r($page_meta, 1) . "</pre>";

            ## Сохраняем языковую версию в БД
            $page_meta->save();
            $page_meta->touch();
		}
		return $page->id;
	}

}
