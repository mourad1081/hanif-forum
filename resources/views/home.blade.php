@extends('layouts.app')

@section('content')

<div class="main-cover container-fluid">
    <div style="width: 100%;
                height: 100%;
                padding: 10px;
                background-color: rgba(0,0,0,0.5);
                text-align: center;
                color: white;
                padding-top: 20px;
                font-family: 'Merriweather', 'Times New Roman', serif;">
        <h1 class="h1" style="font-family: 'Abril Fatface', cursive; font-size: 4rem; text-shadow: 0px 0px 50px #f4a261;">Hanif Forum</h1>
    </div>
</div>


<main id="home" class="container-fluid" style="position: relative;z-index: 1;">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    @includeWhen(Route::currentRouteName() == 'home', 'shared.forum')
                    @includeWhen(Route::currentRouteName() == 'section', 'shared.section')
                    @includeWhen(Route::currentRouteName() == 'discussion', 'shared.discussion')
                    @includeWhen(Route::currentRouteName() == 'create-discussion', 'shared.create-discussion', ['category' => $current_category])
                </div>
            </div>
        </div>
    </div>
</main>
@endsection