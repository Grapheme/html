@extends(Helper::acclayout())

@section('style')
{{ HTML::style('css/redactor.css') }}
@stop

@section('content')
    <h1>Проекты: Новый проект</h1>
{{ Form::open(array('url'=>link::auth($module['rest'].'/store'), 'role'=>'form', 'class'=>'smart-form', 'id'=>'project-form', 'method'=>'post')) }}
	<div class="row margin-top-10">
		<section class="col col-6">
			<div class="well">
				<header>Для создания нового проекта заполните форму:</header>
				<fieldset>
				    <section>
                        <label class="label">Клиент</label>
                        <label class="select">
                        <?php $users = []; ?>
                        @foreach(User::where('group_id',2)->where('active',1)->get() as $user)
                            <?php $users[$user->id] = $user->name.' '.$user->surname; ?>
                        @endforeach
                            {{ Form::select('user_id',$users)  }}
                        </label>
                    </section>
					<section>
						<label class="label">Название</label>
						<label class="input">
							{{ Form::text('title', '') }}
						</label>
					</section>
                    <section>
						<label class="label">Описание проекта</label>
						<label class="textarea">
							{{ Form::textarea('description', '',array('class'=>'redactor')) }}
						</label>
					</section>
                </fieldset>
				<footer>
					<a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{URL::previous()}}">
						<i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
					</a>
					<button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
						<i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Создать</span>
					</button>
				</footer>
			</div>
		</section>
	</div>
{{ Form::close() }}
@stop


@section('scripts')
    <script>
    var essence = 'project';
    var essence_name = 'проект';
	var validation_rules = {
		title: { required: true },
		user_id: { required: true, min: 1 },
	};
	var validation_messages = {
		title: { required: 'Укажите название' },
		user_id: { required: 'Укажите клиента', min: 'Укажите клиента' },
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