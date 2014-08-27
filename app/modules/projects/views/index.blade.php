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
                    <i class="fa-fw fa fa-copy"></i> {{ $project->title }}
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <p>Суммарный прогресс</p>
                <div class="progress">
                    <div style="width: {{ calcProgressPercent($project->pages,0) }}%" class="progress-bar progress-bar-success">
                        <span class="sr-only">{{ calcProgressPercent($project->pages,0) }}% Complete (success)</span>
                    </div>
                    <div style="width: {{ calcProgressPercent($project->pages,1) }}%" class="progress-bar progress-bar-warning">
                        <span class="sr-only">{{ calcProgressPercent($project->pages,1) }}% Complete (warning)</span>
                    </div>
                    <div style="width: {{ calcProgressPercent($project->pages,2) }}%" class="progress-bar progress-bar-danger">
                        <span class="sr-only">{{ calcProgressPercent($project->pages,2) }}% Complete (danger)</span>
                    </div>
                </div>
                @if($project->pages->count())
                <p>Список страниц</p>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Страница</th>
                            <th>Готовность, %</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($project->pages as $index => $page)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ !empty($page->link) ? HTML::link($page->link,$page->title,array('target'=>'_blank')) : $page->title }}</td>
                            <td>
                                <div class="progress progress-striped">
                                @if($page->progress < 20)
                                    <?php $progressbar_status = 'progress-bar-danger'; ?>
                                @elseif($page->progress < 70)
                                    <?php $progressbar_status = 'progress-bar-warning'; ?>
                                @else
                                    <?php $progressbar_status = 'progress-bar-success'; ?>
                                @endif
                                    <div style="width: {{ $page->progress }}%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="{{ $page->progress }}" role="progressbar" class="progress-bar {{ $progressbar_status }}">
                                        <span class="sr-only">{{ $page->progress }}% Complete (success)</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
@section('scripts')

@stop