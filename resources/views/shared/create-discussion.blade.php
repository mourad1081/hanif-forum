@auth
    <!-- Create the editor container -->
    <div id="create-discusion">
        <div class="header">
            <a class="text-left"
               style="position: absolute; left: 10px"
               href="{{ url('/sections/' . $current_category->slug) }}">
                <i class="fas fa-arrow-circle-left"></i> Revenir à la section {{ $current_category->title }}
            </a>
            <img src="{{ asset('img/basmala.png') }}" alt="BismiLlahi rahmaani rahiim" height="32px" style="position: absolute; right: 10px">
            <h3 class="h3 title">Créer une discussion</h3>
            <p class="title text-center">{{ $current_category->title }}</p>
        </div>
        <p class="alert text-center">
            N'oubliez pas de respecter <a href="#" data-izimodal-open="#modal-register">la charte</a> lorsque vous conversez avec les membres de Hanif Forum.
        </p>
        <form action="{{ url('/sections/' . $current_category->slug . '/create-discussion') }}" method="post">
            <div class="form-group">
                <label for="title" class="control-label col-12 title">Titre de la discussion</label>
                <div class="col-12">
                    <input type="text"
                           class="form-control"
                           id="title"
                           name="title"
                           aria-describedby="title-help"
                           placeholder="Titre de la discussion">
                </div>

            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <hr>
            <div class="form-group">
                <label for="content" class="control-label col-12 title text-center">Contenu de la discussion</label>
                <p class="alert alert-warning text-center">
                    Si vous avez besoin d'aide, n'hésitez pas à consulter l'aide pour notamment, inclure aisément des
                    versets coraniques, images, vidéos, formules mathématiques en \( \LaTeX \), etc.
                    <br>
                    <a href="#">Afficher l'aide</a>
                </p>
                <div class="col-12">
                    <textarea name="content" id="content" rows="10"></textarea>
                </div>
                {!! csrf_field() !!}
            </div>
            <div class="text-center">
                <button type="submit" class="btn bg-orange text-white">
                    <i class="fa fa-plus"></i> Créer la discussion
                </button>
            </div>
        </form>
    </div>

    <div id="modal-register">
        <div class="container">
            <div class="row"  style="padding: 25px;">
                <h3 class="h3 title-blue">
                    <img src="{{ asset('img/basmala.jpg') }}" alt="" style="width: 100%;">
                </h3>
                <h4 class="text-center title-blue">
                    As-Salât wa Salâm ‘alâ Rasuli-Llâh Muhammad,
                    As’Salâmu ‘alaykum wa Rahmatullâhi wa Barakâtuh.
                </h4>
                <hr>
                <div style="text-align: justify;">
                    <ul>
                        <li>Les insultes, les moqueries gratuites et les incitations à la haine ou au meurtre (qu’elles soient religieuses, culturelles, idéologiques, sexuelles, raciales, philosophiques, politiques ou autres) sont inacceptables et ne seront donc pas tolérées. Il est donc strictement interdit d’enfreindre cette règle fondamentale.</li>
                        <li>Tous les sujets peuvent être débattus, mais dans le respect du cadre de l’éthique conformément à ce qui a été explicité dans le point précédent.</li>
                        <li>En cas d’harcèlement quelconque d’un membre à l’encontre d’un autre membre, que ce soit en public ou en privé, une sanction sera appliquée.</li>
                        <li>La mixité n’est pas prohibée, à condition qu’il n’y ait pas d’insultes, de propos trop intimes exposés publiquement ni de drague évidente ou «subtile».</li>
                        <li>Avant d’initier un nouveau fil de discussion, vérifiez d’abord qu’il n’existe pas déjà un autre fil de discussion sur le même sujet. Choisissez aussi la bonne catégorie.</li>
                        <li>Evitez de poster plusieurs petits commentaires d’affilés quand vous pouvez tout réunir en un seul commentaire.</li>
                        <li>Si vous avez un problème avec un membre du groupe, vous pouvez envoyer votre réclamation à l’un des admins ou des modérateurs du groupe.</li>
                        <li>N’écrivez pas en langage sms, tâchez donc d’écrire avec le plus grand soin (ponctuations, phrases complètes, etc.).</li>
                    </ul>
                </div>
                <hr>
                <div class="text-center col-12">
                    <button class="btn bg-orange text-white" data-izimodal-close="">Fermer</button>
                </div>
            </div>
        </div>
    </div>


@section('more_css')
    <script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('more_js')
    <script>
        $('#modal-register').iziModal({
            title:'Charte de Hanif Forum',
            headerColor: '#d27d3f'
        });

        CKEDITOR.replace('content');
    </script>
@endsection
@endauth


