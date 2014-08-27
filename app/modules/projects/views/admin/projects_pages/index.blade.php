@extends(Helper::acclayout())

@section('content')
    <h1>Проекты: Страницы проекта &laquo;{{ $project->title }}&raquo;</h1>
    <h4>Клиент: {{ $project->user->name.' '.$project->user->surname }} </h4>
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-bottom-25 margin-top-10">
    		<div class="pull-left margin-right-10">
    		    <a class="btn btn-default" href="{{ link::auth('projects') }}">Список проектов</a>
    		@if(Allow::action('projects', 'project_create'))
    			<a class="btn btn-primary" href="{{ URL::route('projects_pages_create',array('project_id'=>$project->id)) }}">Новая страница проекта</a>
    		@endif
    		</div>
    	</div>
    </div>

    @if($pages->count())
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
    		<table class="table table-striped table-bordered">
    			<thead>
    				<tr>
    				    <th class="col-lg-1 text-center" style="white-space:nowrap;">№</th>
                        <th class="col-lg-6 text-center" style="white-space:nowrap;">Название</th>
                        <th class="col-lg-1 text-center" style="white-space:nowrap;">Прогресc</th>
                        <th class="col-lg-3 text-center" style="white-space:nowrap;">Действия</th>
    				</tr>
    			</thead>
    			<tbody>
    			@foreach($pages as $index => $page)
    				<tr class="vertical-middle">
    				    <td class="col-lg-1 text-center">{{ $index+1 }}</td>
    					<td>{{ !empty($page->link) ? HTML::link($page->link,$page->title,array('target'=>'_blank')) : $page->title }}</td>
    					<td class="col-lg-1 text-center">{{ $page->progress }} %</td>
    					<td class="text-center" style="white-space:nowrap;">
        					@if(Allow::action($module['group'], 'project_edit'))
        					<a href="{{ URL::route('projects_pages_edit',array('project_id'=>$project->id,'page_id'=>$page->id)) }}" class="btn btn-success margin-right-10">Изменить</a>
                    		@endif
        					@if(Allow::action($module['group'], 'project_delete'))
							<form method="DELETE" action="{{ URL::route('projects_pages_delete',array('project_id'=>$project->id,'page_id'=>$page->id)) }}" style="display:inline-block">
								<button type="submit" class="btn btn-danger remove-project-page">
									Удалить
								</button>
							</form>
                    		@endif
    					</td>
    				</tr>
    			@endforeach
    			</tbody>
    		</table>
    	</div>
    </div>
    @else
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    		<div class="ajax-notifications custom">
    			<div class="alert alert-transparent">
    				<h4>Список пуст</h4>
    				В данном разделе находится список страниц проекта
    				<p><br><i class="regular-color-light fa fa-th-list fa-3x"></i></p>
    			</div>
    		</div>
    	</div>
    </div>
@endif

@stop


@section('scripts')
    <script>
    var essence = 'project-page';
    var essence_name = 'страницу проекта';
	var validation_rules = {};
	var validation_messages = {};
    </script>
	<script src="{{ url('js/modules/standard.js') }}"></script>
	<script type="text/javascript">
		if(typeof pageSetUp === 'function'){pageSetUp();}
		if(typeof runFormValidation === 'function'){
			loadScript("{{ asset('js/vendor/jquery-form.min.js') }}", runFormValidation);
		}else{
			loadScript("{{ asset('js/vendor/jquery-form.min.js') }}");
		}
	</script>
@stop
