@extends('layouts.app')

@section('content')
<main id="login" class="container">
    <div class="row">
        <div class="col-12">
            <div class="header">
                <h3 class="h3 title title-blue">Se connecter</h3>
            </div>


        </div>
        <form class="form col-12" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="col-12 title control-label">Adresse e-mail</label>

                <div class="col-12">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="col-12 title control-label">Mot de passe</label>

                <div class="col-12">
                    <input id="password" type="password" class="form-control" name="password" required>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Se souvenir de moi
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-12 text-center">
                    <button type="submit" class="btn bg-orange text-white">
                        Se connecter
                    </button>
                    <br>
                    <small>
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            Mot de passe oublié ?
                        </a>
                    </small>

                </div>
            </div>
        </form>
    </div>
</main>
@endsection
