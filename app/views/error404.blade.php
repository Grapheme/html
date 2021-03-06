@extends(Helper::layout())

@section('content')
<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-center error-box">
                        <h1 class="error-text-2 bounceInDown animated"> Ошибка 404 <span class="particle particle--c"></span><span class="particle particle--a"></span><span class="particle particle--b"></span></h1>
                        <h2 class="font-xl"><strong><i class="fa fa-fw fa-warning fa-lg text-warning"></i> Страница <u>не</u> найдена</strong></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')

@stop
