@if(Auth::check())
    @if(Auth::user()->group_id == 2)
        <?php $user_id = Auth::user()->id; ?>
        <?php $user_name = Auth::user()->name; ?>
        <?php $user_surname = Auth::user()->surname; ?>
    @elseif(isset($project))
        <?php $user_id = $project->user->id; ?>
        <?php $user_name = $project->user->name; ?>
        <?php $user_surname = $project->user->surname; ?>
    @endif
<aside id="left-panel">
    <div class="login-info">
        <span>{{ $user_name.' '.$user_surname }}</span>
    </div>
    <nav>
        <ul>
        <?php $projects_list =  Projects::where('user_id',$user_id)->with('pages')->get(); ?>
        @foreach($projects_list as $projects)
            @if($projects->arhive == 0)
                <li> {{ HTML::link('project/'.$projects->slug,$projects->title.' ('.calcProgressPercent($projects->pages,0).'%)') }}</li>
            @endif
        @endforeach
        @if(Projects::where('user_id',$user_id)->where('arhive',1)->count())
        <span class="label label-info">Архив</span>
        @endif
        @foreach($projects_list as $projects)
            @if($projects->arhive == 1)
                <li> {{ HTML::link('project/'.$projects->slug,$projects->title.' ('.calcProgressPercent($projects->pages,0).'%)') }}</li>
            @endif
        @endforeach
        </ul>
    </nav>
</aside>
@endif