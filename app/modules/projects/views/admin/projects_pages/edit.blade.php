@extends(Helper::acclayout())
@section('style')
    {{ HTML::style('css/redactor.css') }}
@stop
@section('content')
    <h1>Проекты: Редактирование страницы проекта &laquo;{{ $project->title }}&raquo;</h1>
{{ Form::model($project_page, array('url'=>URL::route('projects_pages_update',array('project_id'=>$project->id,'page_id'=>$project_page->id)), 'class'=>'smart-form', 'id'=>'project-page-form', 'role'=>'form', 'method'=>'post')) }}
	{{ Form::hidden('project_id') }}
	<div class="row margin-top-10">
		<section class="col col-6">
			<div class="well">
				<header>Для изменения страницы проекта отредактируйте форму:</header>
				<fieldset>
                    <section>
                        <label class="label">Название</label>
                        <label class="input">
                            {{ Form::text('title') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Ссылка на страницу</label>
                        <label class="input">
                            {{ Form::text('link') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Прогресc</label>
                        <label class="input">
                            {{ Form::text('progress') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Ссылка на макет</label>
                        <label class="input">
                            {{ Form::text('maket') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Описание проекта</label>
                        <label class="textarea">
                            {{ Form::textarea('description',NULL,array('class'=>'redactor')) }}
                        </label>
                    </section>
                </fieldset>
				<footer>
					<a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{ URL::previous() }}">
						<i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
					</a>
					<button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
						<i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Изменить</span>
					</button>
				</footer>
			</div>
		</section>
	</div>
{{ Form::close() }}
@stop


@section('scripts')
    <script>
    var essence = 'project-page';
    var essence_name = 'страница';
    var validation_rules = {
        title: { required: true },
        project_id: { required: true, min: 1 },
    };
    var validation_messages = {
        title: { required: 'Укажите название' },
        project_id: { required: 'Укажите проект', min: 'Укажите проект' },
    };
    </script>
    {{ HTML::script('js/modules/standard.js') }}
    <script type="text/javascript">
        if(typeof pageSetUp === 'function'){pageSetUp();}
        if(typeof runFormValidation === 'function'){
            loadScript("{{ asset('js/vendor/jquery-form.min.js') }}", runFormValidation);
        }else{
            loadScript("{{ asset('js/vendor/jquery-form.min.js') }}");
        }
    </script>
    {{ HTML::script('js/vendor/redactor.min.js') }}
    {{ HTML::script('js/system/redactor-config.js') }}
@stop