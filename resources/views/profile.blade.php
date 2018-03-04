@extends('layouts.app')


@section('content')
    <?php use \Carbon\Carbon; ?>

    <div class="main-cover container-fluid">
        <div style="width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                text-align: center;
                color: white;
                padding: 20px 10px 10px;
                font-family: 'Merriweather', 'Times New Roman', serif;">
            <h1 class="h1">
                {{ URL::current() == url('/mon-profil') ? 'Mon profil' : 'Profil de ' . $user->pseudo }}
            </h1>
        </div>
    </div>

    <main id="profile" class="container">
        <div id="profile-cover"></div>
        
        <div id="profile-body-container" class="container">

            <div class="row">

                <div id="profile-sidebar-wrapper" class="col-12 col-md-3 text-center">
                    <div id="profile-sidebar">
                        <img id="profile-picture" height="64px" src="{{ asset($user->picture) }}" alt="profile picture">
                        <!-- Si c'est le profil du gars connecté qui visite  -->
                        @if(URL::current() == url('/mon-profil'))
                            <form id="upload-picture"
                                  method="post"
                                  action="{{ url('/users/' . $user->pseudo . '/update-picture') }}">
                                {!! csrf_field() !!}
                                <div class="fallback">
                                    <input name="file" type="file" multiple />
                                </div>
                            </form>
                        @endif
                        <hr>
                        <p><strong>{{ $user->pseudo }}</strong></p>

                        <p class="text-center grade">
                            {{ $user->grade }}
                        </p>
                        <p>Prochain grade: <strong>{{ $nextGrade->title }}</strong></p>
                        <p>{{ $user->total_answers }} / {{ $nextGrade->min_posts }} <i class="fa fa-comments"></i></p>
                        <div style="padding: 10px;">
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-light-green"
                                     role="progressbar"
                                     aria-valuenow="75"
                                     aria-valuemin="0"
                                     aria-valuemax="100"
                                     style="width: {{ $percentage }}%;display: block;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="profile-body" class="col-12 col-md-9">
                    @if($user->activated == '1')
                        <h5 class="h5 title"><i class="fab fa-leanpub"></i> À propos</h5>
                        <div class="about">
                            @if(URL::current() == url('/mon-profil'))
                                <div class="text-right">
                                    <a class="btn" href="#" data-izimodal-open="#modal-about">
                                        <i class="fas fa-pencil-alt"></i> Modifier
                                    </a>
                                </div>
                            @endif
                            <em>{!! $user->about ?? "Rien d'écrit :'(" !!}</em>
                        </div>

                        <hr>

                        <h5 class="h5 title"><i class="fab fa-stack-exchange"></i> Dernières discussions</h5>
                        @if(isset($discussions) and count($discussions) > 0)
                            @foreach($discussions as $discussion)
                                <p>
                                    <a href="{{ url('discussions/' . $discussion->id) }}">
                                        <i class="fas fa-angle-right"></i>
                                        {{ $discussion->title }}
                                    </a>
                                    <span class="float-right">
                                <i class="fa fa-comments"></i> {{ $discussion->answers }} <i class="fa fa-eye"></i> {{ $discussion->views }}
                            </span>
                                </p>
                                <hr>
                            @endforeach
                        @else
                            <p class="text-center">Vide</p>
                        @endif

                        <h5 class="h5 title"><i class="fa fa-comments"></i> Derniers messages</h5>
                        @foreach($posts as $post)
                            <small><em>{{ $post->created_at->diffForHumans() }}, le {{ $post->created_at->formatLocalized('%d/%m/%Y') }} </em></small>
                            {!! $post->makeExcerpt() !!}
                            <a class="btn btn-block" style="background-color: buttonface;" href="{{ url('discussions/' . $post->discussion_id) }}">
                                Voir ce message dans son contexte
                            </a>
                            <hr>
                        @endforeach
                    @else
                        <p class="alert alert-warning text-center">
                            Votre compte n'a pas encore été activé. Revenez plus tard ;-)
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div id="modal-about">
            <div class="container" style="padding-top: 10px;">
                <div class="row">
                    <form action="{{ '#' }}" method="post" class="form col-12">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="about" class="title"><i class="fab fa-leanpub"></i> Veuillez entrer un nouvel "à propos"</label>
                            <textarea id="about" rows="10" name="about" class="form-control col-12">{!! $user->about !!}</textarea>
                        </div>
                        <hr>
                        <div class="text-center" style="margin-top: 10px;margin-bottom: 10px;">
                            <button id="btn-validate-grade" class="btn bg-orange text-white"><i class="fas fa-check"></i> Valider</button>
                            <button class="btn btn-default" data-izimodal-close="">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>
@endsection

@section('more_css')
    <link rel="stylesheet" href="{{ asset("plugins/dropzone/dropzone.min.css") }}" type="text/css">
@endsection

@section('more_js')
    <script src="{{ asset('plugins/dropzone/dropzone.min.js') }}"></script>
    <script>
        $(function () {
            var dropzone = $('#upload-picture').dropzone({
                init: function() {
                    this.on("success", function(file, response) {
                        if (response === '1')
                            location.reload();
                    });
                }
            });
            dropzone.addClass('dropzone');

            $('#modal-about').iziModal({
                title: '<i class="fas fa-pencil-alt"></i> Modifier votre bio',
                closeOnEscape: true,
                closeButton: true,
                headerColor: '#ff9269'
            });
        });
    </script>
@endsection