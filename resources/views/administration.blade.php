@extends('layouts.app')

@section('content')
    <main id="admin" class="container-fluid">
        <h1 class="h1 title text-center">Administration</h1>
        <hr>
        <div class="row">
            <div id="container-manage-categories" class="col-12">
                <h4 class="h4 title">Gestion des catégories <button id="btn-add-category" class="btn bg-warning"><i class="fas fa-plus"></i> Ajouter une catégorie</button></h4>
                <p class="alert alert-warning">
                    Lorsque vous archivez une catégorie, notez bien que toutes les sous-catégories de celle que vous
                    archivez le seront également.
                </p>
                <div class="table-wrapper">
                    <table id="table-cat-unarchived" class="table table-striped table-hover table-sm ">
                        <thead>
                        <tr>
                            <th scope="col">Actions</th>
                            <th scope="col">ID</th>
                            <th scope="col">ID parent</th>
                            <th scope="col">Accès à</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Description</th>
                            <th scope="col">Messages</th>
                            <th scope="col">Sujets</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            @if($category->visible == 1)
                                <tr data-cat-id="{{ $category->id }}" data-cat-pid="{{ $category->parent_id }}" class="{{ $category->parent_id == null ? "bg-beige" : "" }}">
                                    <td class="td-actions">
                                        <button class="btn btn-sm btn-warning btn-update-category"
                                                data-category-id="{{ $category->id }}"
                                                data-category-slug="{{ $category->slug }}"
                                                data-category-title="{{ $category->title }}"
                                                data-category-description="{{ $category->description }}"
                                                data-category-min-access-level="{{ $category->min_access_level }}"
                                                data-category-parent-id="{{ $category->parent_id }}">
                                            Modifier
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-archive-category"
                                                data-category-id="{{ $category->id }}"
                                                data-category-slug="{{ $category->slug }}">
                                            Archiver
                                        </button>
                                    </td>
                                    <td class="td-cat-id">{{ $category->id }}</td>
                                    <td class="td-cat-pid">{{ $category->parent_id ?? 'Pas de parent' }}</td>
                                    <td class="td-cat-access w-15">{{ $category->min_access_level == 0 ? 'Tout le monde' : ($category->min_access_level == 1 ? 'Modérateurs et administrateurs' : 'Administrateurs') }}</td>
                                    <td class="td-cat-title w-15">{{ $category->title }}</td>
                                    <td class="td-cat-descr">{{ $category->makeExcerpt() }}</td>
                                    <td class="td-cat-answers">{{ $category->nb_messages }}</td>
                                    <td class="td-cat-topics">{{ $category->nb_discussions }}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>


                <hr>


                <h4 class="h4 title">Catégories archivées</h4>
                <p class="alert alert-primary">
                    Lorsque vous désarchivez une catégorie, notez bien qu'il faut d'abord désarchiver sa catégorie parent
                    si elle est aussi archivée.
                </p>
                <div class="table-wrapper">
                    <table id="table-cat-archived" class="table table-striped table-hover table-sm ">
                        <thead>
                        <tr>
                            <th scope="col">Actions</th>
                            <th scope="col">ID</th>
                            <th scope="col">ID parent</th>
                            <th scope="col">Accès à</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Description</th>
                            <th scope="col">Messages</th>
                            <th scope="col">Sujets</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            @if($category->visible == 0)
                                <tr data-cat-id="{{ $category->id }}" data-cat-pid="{{ $category->parent_id }}" class="{{ $category->parent_id == null ? "bg-beige" : "" }}">
                                    <td class="td-actions">
                                        <button class="btn btn-sm btn-warning btn-update-category"
                                                data-category-id="{{ $category->id }}"
                                                data-category-slug="{{ $category->slug }}"
                                                data-category-title="{{ $category->title }}"
                                                data-category-description="{{ $category->description }}"
                                                data-category-parent-id="{{ $category->parent_id }}">
                                            Modifier
                                        </button>
                                        <button class="btn btn-sm btn-primary btn-unarchive-category"
                                                data-category-id="{{ $category->id }}"
                                                data-category-slug="{{ $category->slug }}">
                                            Désarchiver
                                        </button>
                                    </td>
                                    <td class="td-cat-id">{{ $category->id }}</td>
                                    <td class="td-cat-pid">{{ $category->parent_id ?? 'Pas de parent' }}</td>
                                    <td class="td-cat-access w-15">{{ $category->min_access_level == 0 ? 'Tout le monde' : ($category->min_access_level == 1 ? 'Modérateurs et administrateurs' : 'Administrateurs') }}</td>
                                    <td class="td-cat-title w-15">{{ $category->title }}</td>
                                    <td class="td-cat-descr">{{ $category->makeExcerpt() }}</td>
                                    <td class="td-cat-answers">{{ $category->nb_messages }}</td>
                                    <td class="td-cat-topics">{{ $category->nb_discussions }}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <hr>


            </div>


            <div id="container-manage-users" class="col-12">
                <h4 class="h4 title">Gestion des utilisateurs</h4>
                <div class="table-wrapper">
                    <table class="table table-striped table-hover table-sm ">
                        <thead>
                        <tr>
                            <th scope="col">Actions</th>
                            <th scope="col">ID</th>
                            <th scope="col">pseudo</th>
                            <th scope="col">Grade</th>
                            <th scope="col">Grade custom</th>
                            <th scope="col">Nombre de messages</th>
                            <th scope="col">Date création</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-warning dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false">
                                            Choisir une action
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-left">
                                            <button class="dropdown-item" type="button"><i class="fas fa-ban"></i> Bannir</button>
                                            <button class="dropdown-item" type="button"><i class="fas fa-check"></i> Valider son inscription</button>
                                            <button class="dropdown-item" type="button"><i class="fas fa-tags"></i> Changer son rôle</button>
                                            <button class="dropdown-item" type="button"><i class="fas fa-graduation-cap"></i> Donner un grade</button>
                                            <button class="dropdown-item" type="button"><i class="fas fa-user"></i> Voir le profil</button>
                                            <button class="dropdown-item" type="button"><i class="fas fa-images"></i> Retirer la photo</button>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->id != null ? $category->id : '===' }}</td>
                                <td>{{ $user->pseudo }}</td>
                                <td>{{ $user->grade }}</td>
                                <td>{{ $user->custom_grade ?? '/' }}</td>
                                <td>{{ $user->total_answers }}</td>
                                <td>{{ $user->created_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="modal-update-category">
            <div class="container" style="padding-top: 10px;">
                <div class="row">
                    <div class="col-12">
                        <form id="form-update-category" method="post" action="{{ url('/categories/#/update') }}">
                            <div class="form-group">
                                <label for="category-title" class="title"><i class="fas fa-tag"></i> Nouveau nom de la catégorie</label>
                                <input class="form-control" id="category-title" placeholder="Nom catégorie" name="title" value="">
                            </div>
                            <div class="form-group">
                                <label for="category-parent-id" class="title"><i class="fas fa-tags"></i> Catégorie parent</label>
                                <select class="form-control" id="category-parent-id" name="parent_id">
                                    <option value="null">Pas de catégorie parent</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">
                                            @if($category->parent_id == null)
                                                === {{ $category->title }} ===
                                            @else
                                                {{ $category->title }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="category-parent-id" class="title"><i class="fas fa-unlock-alt"></i> Catégorie réservée pour</label>
                                <select class="form-control" id="category-min-access-level" name="min_access_level">
                                    <option value="0">Tout le monde</option>
                                    <option value="1">Les Modérateurs et administrateurs</option>
                                    <option value="99">Seulement les administrateurs</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="category-description" class="title"><i class="fas fa-align-left"></i> Description</label>
                                <small>(Optionnel)</small>
                                <textarea name="description"
                                          id="category-description"
                                          class="form-control"></textarea>
                            </div>
                            {!! csrf_field() !!}

                            <hr>

                            <div class="text-center" style="margin-top: 10px;margin-bottom: 10px;">
                                <button id="btn-validate-update-category" class="btn"  style="background-color:#ff9269; color: white;"><i class="fas fa-check"></i> Valider</button>
                                <button class="btn btn-default" data-izimodal-close="">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
