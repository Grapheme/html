@extends(Helper::acclayout())

@section('content')
    <h1>Проекты</h1>

    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-bottom-25 margin-top-10">
    		<div class="pull-left margin-right-10">
    		@if(Allow::action('projects', 'project_create'))
    			<a class="btn btn-primary" href="{{ link::auth($module['rest'].'/create') }}">Новый проект</a>
    		@endif
    		</div>
    	</div>
    </div>

    @if($projects->count())
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
    		<table class="table table-striped table-bordered">
    			<thead>
    				<tr>
    				    <th class="col-lg-1 text-center" style="white-space:nowrap;">№</th>
                        <th class="col-lg-6 text-center" style="white-space:nowrap;">Название</th>
                        <th class="col-lg-1 text-center" style="white-space:nowrap;">Прогресc</th>
                        <th class="col-lg-1 text-center" style="white-space:nowrap;">Страницы</th>
                        <th class="col-lg-3 text-center" style="white-space:nowrap;">Действия</th>
    				</tr>
    			</thead>
    			<tbody>
    			@foreach($projects as $index => $project)
    				<tr class="vertical-middle">
    				    <td class="col-lg-1 text-center">{{ $index+1 }}</td>
    					<td>{{ HTML::link('project/'.$project->slug,$project->title,array('target'=>'_blank')) }}</td>
    					<td class="col-lg-1 text-center">{{ calcProgressPercent($project->pages,0) }} %</td>
    					<td><a href="{{ URL::route('projects_pages_index',array('project_id'=>$project->id)) }}" class="btn btn-default margin-right-10">{{ $project->pages->count() }} шт.</a></td>
    					<td class="text-center" style="white-space:nowrap;">
        					@if(Allow::action($module['group'], 'project_edit'))
        					<a href="{{ link::auth($module['rest'].'/edit/'.$project->id) }}" class="btn btn-success margin-right-10">Изменить</a>
                    		@endif
        					@if(Allow::action($module['group'], 'project_delete'))
							<form method="POST" action="{{ link::auth($module['rest'].'/destroy/'.$project->id) }}" style="display:inline-block">
								<button type="submit" class="btn btn-danger remove-project">
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
    				В данном разделе находится список проектов
    				<p><br><i class="regular-color-light fa fa-th-list fa-3x"></i></p>
    			</div>
    		</div>
    	</div>
    </div>
@endif

@stop


@section('scripts')
    <script>
    var essence = 'project';
    var essence_name = 'проект';
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
