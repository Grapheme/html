@extends(Helper::layout())
@section('style')

@stop
@section('content')
@include('templates.site.sidebar')
<div id="main" role="main">
    <div id="ribbon"></div>
    <div id="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1 class="page-title txt-color-blueDark">
                    <i class="fa-fw fa fa-copy"></i> Мои проекты
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            </div>
        </div>
    </div>
</div>
@stop
@section('scripts')

@stop