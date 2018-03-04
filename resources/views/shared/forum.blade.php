<?php

use App\Category;
use Carbon\Carbon;

function createTable(Category $cat, string $classes) {
    return '<table class="table table-hover table-responsive-sm main-category">
              <thead class="'.$classes.'">
                  <tr>
                      <th class="w-50" scope="col">
                          <a href="' . url('/sections/' . $cat->slug) . '">' . $cat->title . '</a>
                          <small class="d-none d-md-block">' . $cat->description . '</small>
                      </th>
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

function createRow(Category $cat) {
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



<div id="forum" class="container-fluid">
    <div class="text-center">
        <img style="width: 80%" src="{{ asset('img/basmala.jpg') }}" alt="#">
    </div>

    <div class="col-12 alert alert-primary">
        To do :
        <ul>
            <li>Modifier son post original</li>
            <li>Supprimer/archiver une discussion</li>
            <li>Compléter le pied de page</li>
            <li>Améliorer le design</li>
            <li>Mettre à jour sa signature/à propos</li>
            <li>Paginer les discussions, etc. lorsque ça dépasse X messages par page</li>
            <li>Bugs graphiques à corriger (photo de profil, button modifier photo de profil)</li>
        </ul>
    </div>
    
    @if (Auth::check() and Auth::user()->activated == '0')
        <p class="alert alert-warning text-center" style="font-weight: 600;">
            Votre compte n'a pas encore été validé par nos modérateurs.
            Vous ne pourrez naviguer qu'une fois celui-ci activé ;-)
        </p>
    @endif

    @if(isset($discussions) and count($discussions) > 0)
        @foreach($discussions as $discussion)
            <p><a href="{{ url('discussions/' . $discussion->id) }}">{{ $discussion->title }}</a></p>
            <p>answers : {{ $discussion->answers }}</p>
            <p>views : {{ $discussion->views }}</p>
            <hr>
        @endforeach
    @endif
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
</div>



