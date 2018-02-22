<?php

use App\Category;use Carbon\Carbon;

function createTable(\App\Category $cat, string $classes) {
    return '<table class="table table-sm table-hover table-responsive-sm main-category">
              <thead class="bg-dark-green" style="color: white">
                  <tr>
                      <th class="w-50" scope="col">Nom de la sous-catégorie</th>
                      <th class="w-15 text-right" scope="col"><i class="fab fa-stack-exchange"></i> Sujets</th>
                      <th class="w-15 text-right" scope="col"><i class="fa fa-comments"></i> Messages</th>
                      <th class="w-20 text-right" scope="col"><i class="far fa-clock"></i> Dernier sujet</th>
                  </tr>
              </thead>
              <tbody>';
}

function closeTable() {
    return '</tbody></table>';
}

function createRow(\App\Category $cat) {
    $x = null;
    if ($cat->last_discussion_id == null)
        $x = "<small>Pas de sujet.</small>";
    else
        $x = '<small><a href="'. url('/discussions/' . $cat->last_discussion_id ) .'">' . $cat->last_discussion_title . '</a></small><br>
              <small>Posté par <a href="'. url('/users/' . $cat->last_discussion_author) .'">' . $cat->last_discussion_author . '</a>, ' . Carbon::createFromTimestamp(strtotime($cat->last_discussion_created_at))->diffForHumans()  . '</small>';

    return '<tr>
              <td class="w-50">
                  <a href="' . url('/sections/' . $cat->slug) . '"><i class="fas fa-angle-right"></i> ' . $cat->title . '</a><br>
                  <small class="d-none d-md-block">' . $cat->description . '</small>
              </td>
              <td class="w-15 text-right"><i class="fab fa-stack-exchange"></i> ' . $cat->nb_discussions . '</td>
              <td class="w-15 text-right"><i class="fa fa-comments"></i> ' . $cat->nb_messages . '</td>
              <td class="w-20 text-right">
                  ' . $x . '
              </td>
            </tr>';
}


/**
 * @param $category
 * @param string $class
 * @return string
 */
function toTable($category, string $class)
{
    $html = createTable($category['value'], $class);
    foreach($category['children'] as $sub_category)
        $html .= createRow($sub_category);
    return $html . closeTable();
}

?>



<div id="forum" class="container-fluid" style="position: relative;">
    <div class="header">
        <a href="{{ url('/')  }}" >
            <i class="fas fa-arrow-circle-left"></i> Revenir à la page principale
        </a>

        <img src="{{ asset('img/basmala.png') }}" alt="BismiLlahi rahmaani rahiim" height="32px" style="position: absolute; right: 10px">

        @if(isset($current_category))
            <h1 class="title text-center">{{ $current_category->title }}</h1>
        @endif

        <p class="text-center" style="margin-top: 15px;"><a href="{{ url('/sections/' . $current_category->slug . '/create-discussion') }}"><i class="fa fa-plus"></i> Créer une discussion</a></p>

    </div>

    @if($categories)
        <hr>
        @foreach($categories as $category)
            <?php /** @var Category $category['value'] */?>
            @if($category['value']->isNormalCategory())
                {!! toTable($category, "bg-dark-green text-white") !!}
            @elseif($category['value']->isVIPCategory() and Auth::check() and Auth::user()->isAtLeastVIP())
                {!! toTable($category, "bg-light-green") !!}
            @elseif($category['value']->isAdminCategory() and Auth::check() and Auth::user()->isAdmin())
                {!! toTable($category, "bg-orange text-white") !!}
            @endif
        @endforeach
    @endif
    <hr>
    <h4 class="title text-center">Discussions</h4>
    <hr>
    @if(isset($discussions) and count($discussions) > 0)
        <table class="table table-sm table-hover table-responsive-sm table-light">
            <thead>
            <tr>
                <th class="w-40" scope="col"><i class="fab fa-stack-exchange"></i> Titre</th>
                <th class="w-15 text-right" scope="col"><i class="fas fa-user"></i> Auteur</th>
                <th class="w-15 text-right" scope="col"><i class="fas fa-eye"></i> Vus</th>
                <th class="w-15 text-right" scope="col"><i class="fa fa-comments"></i> Messages</th>
                <th class="w-30 text-right" scope="col"><i class="far fa-clock"></i> Dernier message</th>
            </tr>
            </thead>
            <tbody>

            @foreach($discussions as $discussion)
                <tr>
                    <td class="w-40">
                        @if ($discussion->discussion_sticked_at_top == '1')
                            <img src="{{ asset('img/pin-512.png') }}"
                                 style="width: 16px;"
                                 data-toggle="tooltip"
                                 data-placement="top"
                                 title="Discussion épinglé">
                        @endif
                        <a href="{{ url('/discussions/' . $discussion->discussion_id) }}">{{ $discussion->discussion_title }}</a>
                    </td>
                    <td class="w-15 text-right"><a href="{{ url('/users/' . $discussion->author_pseudo) }}">{{ $discussion->author_pseudo }}</a></td>
                    <td class="w-15 text-right">{{ $discussion->discussion_views }}</td>
                    <td class="w-15 text-right">{{ $discussion->discussion_answers }}</td>

                    <td class="w-30 text-right">{{ Carbon::createFromTimestamp(strtotime($discussion->discussion_updated_at))->diffForHumans() ?? 'Pas de sujet' }}</td>
                </tr>
            @endforeach

            </tbody>
        </table>
    @endif
    <hr>

</div>

@section('more_js')
    <script>
        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endsection



