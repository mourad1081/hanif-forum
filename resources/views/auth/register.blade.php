@extends('layouts.app')

@section('content')
<main id="register" class="container">
    <div class="row">
        <div class="col-12">
            <div class="header">
                <h3 class="h3 title title-blue">Créer un compte</h3>
            </div>

            <p class="text-center">
                Votre compte sera actif dès lors qu'un modérateur ou
                administrateur du forum aura validé votre inscription.
            </p>

            <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('pseudo') ? ' has-error' : '' }}">
                    <label for="pseudo" class="control-label col-12 title"><i class="fas fa-user"></i> Pseudo</label>

                    <div class="col-12">
                        <input id="pseudo"
                               type="text"
                               class="form-control"
                               name="pseudo"
                               value="{{ old('pseudo') }}"
                               required
                               autofocus>

                        @if ($errors->has('pseudo'))
                            <span class="help-block">
                                <strong>{{ $errors->first('pseudo') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="control-label col-12 title"><i class="fas fa-at"></i> Adresse e-mail</label>

                    <div class="col-12">
                        <input id="email"
                               type="email"
                               class="form-control"
                               name="email"
                               value="{{ old('email') }}"
                               required>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="control-label col-12 title"><i class="fas fa-unlock-alt"></i> Mot de passe</label>

                    <div class="col-12">
                        <input id="password"
                               type="password"
                               class="form-control"
                               name="password"
                               required>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="control-label col-12 title"><i class="fas fa-unlock-alt"></i> Confirmer mot de passe</label>

                    <div class="col-12">
                        <input id="password-confirm"
                               type="password"
                               class="form-control"
                               name="password_confirmation"
                               required>
                    </div>
                </div>
                <small class="col-12">
                    <i class="fas fa-check"></i> En créant un compte, vous acceptez <a href="#" data-izimodal-open="#modal-register">la charte</a> de Hanif Forum.
                </small>
                <hr>

                <div class="form-group">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn bg-orange text-white">
                            Créer un compte
                        </button>
                    </div>
                </div>
            </form>
        </div>
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
</main>
@endsection

@section('more_js')
    <script>
        $('#modal-register').iziModal({
            title:'Charte de Hanif Forum',
            headerColor: '#d27d3f'
        });
    </script>
@endsection
