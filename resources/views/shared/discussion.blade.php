<div id="forum" class="container-fluid">

    <div class="header">
        <a href="{{ url('/sections/' . $current_category->slug)  }}">
            <i class="fas fa-arrow-circle-left"></i> Retour <!-- Revenir dans la section {{ $current_category->title }} -->
        </a>

        <img src="{{ asset('img/basmala.png') }}" alt="BismiLlahi rahmaani rahiim" height="32px"  class="float-right">
    </div>

    <div class="post-op row">
        <div class="col-12 col-md-2 post-author">
            <img width="64px"  src="{{ asset('img/user-m.png') }}" alt="">
            <br>
            <strong><a href="{{ url('/users/' . $author->pseudo) }}">{{ $author->pseudo }}</a></strong>
            <small class="post-author-grade d-block">{{ $author->grade ?? 'lol' }}</small>
            <small><i class="fa fa-comments"></i> {{ $author->total_answers }} posts</small>
            <hr>
            @restrictedArea("admin")
                @if($current_discussion->sticked_at_top == '1')
                <a href="{{ url('/discussions/' . $current_discussion->id . '/unpin') }}">
                    <img src="{{ asset('img/pin-512.png') }}"
                         style="width: 16px;"
                         data-toggle="tooltip"
                         data-placement="top"
                         title="Désépingler ce fil" data-original-title="">
                    Désépingler
                </a>
                @else
                <a href="{{ url('/discussions/' . $current_discussion->id . '/pin') }}">
                    <img src="{{ asset('img/pin-512.png') }}"
                         style="width: 16px;"
                         data-toggle="tooltip"
                         data-placement="top"
                         title="Épingler ce fil" data-original-title="">
                    Épingler
                </a>
                @endif
            @endrestrictedArea
            <hr>
            <h1 class="meta-informations"><i class="far fa-heart"></i></h1>
            {{ $current_discussion->likes  }} Mentions "j'aime"
        </div>
        <div class="col-12 col-md-10 post-content">
            <h2 style="border-bottom: solid 1px #264653; color: #264653;padding-bottom: 10px;">
                @if($current_discussion->sticked_at_top == '1')
                    <img src="{{ asset('img/pin-512.png') }}"
                         style="width: 16px; vertical-align: top;"
                         data-toggle="tooltip"
                         data-placement="top"
                         title="Épingler ce fil" data-original-title="">
                @endif
                <strong>{{ $current_discussion->title }}</strong>
            </h2>
            @if($current_discussion->user_id == Auth::user()->id or Auth::user()->isAtLeastVIP())
                <p class="meta-informations text-right">
                    <small>Posté {{ $current_discussion->created_at->diffForHumans() }}, le <em>{{ $current_discussion->created_at->formatLocalized('%d/%m/%Y') }}</em></small>
                    <small><a data-discussion-id="{{ $current_discussion->id }}" class="link-update-discussion" href="#"><i class="fas fa-pencil-alt"></i> Modifier</a></small>
                </p>
            @endif
            <div id="content-{{ $current_discussion->id }}" class="content">
                {!! $current_discussion->content !!}
            </div>
            <div id="content-textarea-discussion-{{ $current_discussion->id }}">

            </div>

            <hr>

            <p class="meta-informations">
                <i class="fa fa-eye"></i> {{ $current_discussion->views }} vues -
                <i class="fa fa-comments"></i> {{ $current_discussion->answers }} réponses
            </p>
        </div>
    </div>


    <div class="col-12">
        <hr>
    </div>

    <div class="container-fluid">
        @foreach($posts as $post)
            <div class="post row">
                <div class="col-12 col-md-2 post-author">
                    <img width="64px"  src="{{ asset('img/user-m.png') }}" alt="">
                    <br>
                    <strong><a href="{{ url('/users/' . $post->pseudo) }}">{{ $post->pseudo }}</a></strong>
                    <small class="post-author-grade d-block">{{ $post->grade_title }}</small>
                    <small><i class="fa fa-comments"></i> {{ $post->total_answers }} posts</small>
                </div>

                <div class="col-12 col-md-10 post-content speech-bubble">
                    @if($post->user_id == Auth::user()->id or Auth::user()->isAtLeastVIP())
                        <p class="text-right">
                            <small>Posté {{ $post->created_at->diffForHumans() }}, le <em>{{ $post->created_at->formatLocalized('%d/%m/%Y') }}</em></small>
                            <small><a data-post-id="{{ $post->post_id }}" class="link-update-message" href="#"><i class="fas fa-pencil-alt"></i> Modifier</a></small>
                        </p>
                    @endif
                    <hr class="hr">
                    <div id="content-{{ $post->post_id }}" class="content">
                        {!! $post->content !!}
                    </div>
                    <div id="content-textarea-{{ $post->post_id }}">

                    </div>
                </div>
            </div>
            <hr>
        @endforeach
    </div>

    @include('shared.post-message')

    <div id="wrapper-update-message">
        <div id="container-update-message" class="d-none" data-currently-updating="false">
            <form id="form-update-message" action="#" data-base-url="{{ url('messages/update') }}" method="post">
                {!! csrf_field() !!}
                <textarea name="content" id="update-message" cols="80" rows="10">lol</textarea>
                <div class="text-center">
                    <button id="submit-update-message"
                            style="margin-top: 10px;"
                            class="btn bg-orange text-white">Modifier le message</button>
                </div>
            </form>
        </div>
    </div>
</div>



