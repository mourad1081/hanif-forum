import iziModal from 'izimodal/js/iziModal';
import {HttpRequest, Notification, TypeNotification, startLoading, stopLoading} from "./core"
import 'izitoast'

export class Administration {

    private btnArchiveCategory:JQuery;
    private btnUpdateCategory:JQuery;
    private btnValidateUpdateCategory:JQuery;
    private formupdateCategory:JQuery;
    private currentCategory:JQuery;
    private modalUpdateRole:any;
    private modalUpdateGrade:any;
    private modalUpdateCategory:any;
    private currentCategoryId:any;
    private updating:boolean;
    private tmpPseudo:string;

    private ban:Function;
    private unban:Function;
    private activate:Function;
    private deactivate:Function;
    private removePicture:Function;
    private setGrade:Function;
    private setRole:Function;

    constructor() {

        this.btnUpdateCategory   = $('.btn-update-category');
        this.btnArchiveCategory  = $('.btn-archive-category');
        this.modalUpdateCategory = $('#modal-update-category');
        this.modalUpdateRole     = $('#modal-set-role');
        this.modalUpdateGrade    = $('#modal-set-grade');
        this.formupdateCategory  = $('#form-update-category');
        this.btnValidateUpdateCategory = $('#btn-validate-update-category');
        this.currentCategoryId = {};
        this.tmpPseudo = null;
        this.updating = false;

        this.modalUpdateCategory.iziModal({
            title: '<i class="fas fa-pencil-alt"></i> Modifier la catégorie',
            closeOnEscape: true,
            closeButton: true,
            headerColor: '#ff9269'
        });

        this.modalUpdateRole.iziModal({
            title: '<i class="fas fa-pencil-alt"></i> Modifier le rôle',
            closeOnEscape: true,
            closeButton: true,
            headerColor: '#ff9269'
        });

        this.modalUpdateGrade.iziModal({
            title: '<i class="fas fa-pencil-alt"></i> Modifier le grade personnalisé',
            closeOnEscape: true,
            closeButton: true,
            headerColor: '#ff9269'
        });

        this.ban = (pseudo) => {
            new HttpRequest('/users/' + pseudo + '/ban', null, (result) => {
                let table = $('#table-manage-users');
                // We set the row as red
                table.find('tr[data-user-pseudo="'+ pseudo +'"]').addClass('table-danger');
                table.find('.dropdown-menu[data-user-pseudo="'+ pseudo +'"] button[data-action="ban"]')
                     .html('<i class="fas fa-ban"></i> Dé-bannir')
                     .attr('data-action', 'unban');
                new Notification("OK", pseudo + " a été banni avec succès !", TypeNotification.Success);
            }, (xhr, error) => {
                new Notification("ban after ajax error", pseudo, TypeNotification.Info);
            }).get();
        };

        this.unban = (pseudo) => {
            new HttpRequest('/users/' + pseudo + '/unban', null, (result) => {
                let table = $('#table-manage-users');
                // We set the row as red
                table.find('tr[data-user-pseudo="'+ pseudo +'"]').removeClass('table-danger');
                table.find('.dropdown-menu[data-user-pseudo="'+ pseudo +'"] button[data-action="unban"]')
                    .html('<i class="fas fa-ban"></i> Bannir')
                    .attr('data-action', 'ban');
                new Notification("OK", pseudo + " a été dé-banni avec succès !", TypeNotification.Success);
            }, (xhr, error) => {
                new Notification("unban after ajax error", pseudo, TypeNotification.Info);
            }).get();
        };

        this.activate = (pseudo) => {
            new HttpRequest('/users/' + pseudo + '/activate', null, (result) => {
                let table = $('#table-manage-users');
                // We set the row as red
                table.find('tr[data-user-pseudo="'+ pseudo +'"]').removeClass('table-warning');
                table.find('.dropdown-menu[data-user-pseudo="'+ pseudo +'"] button[data-action="activate"]')
                    .html('<i class="fas fa-check"></i> Invalider son inscription')
                    .attr('data-action', 'deactivate');
                new Notification("OK", "Le compte de " + pseudo + " a été activé avec succès !", TypeNotification.Success);
            }, (xhr, error) => {
                new Notification("activate after ajax error", pseudo, TypeNotification.Info);
            }).get();
        };

        this.deactivate = (pseudo) => {
            new HttpRequest('/users/' + pseudo + '/deactivate', null, (result) => {
                let table = $('#table-manage-users');
                // We set the row as red
                table.find('tr[data-user-pseudo="'+ pseudo +'"]').addClass('table-warning');
                table.find('.dropdown-menu[data-user-pseudo="'+ pseudo +'"] button[data-action="deactivate"]')
                    .html('<i class="fas fa-check"></i> Valider son inscription')
                    .attr('data-action', 'deactivate');
                new Notification("OK", "Le compte de " + pseudo + " a été désactivé avec succès !", TypeNotification.Success);
            }, (xhr, error) => {
                new Notification("deactivate after ajax error", pseudo, TypeNotification.Info);
            }).get();
        };

        this.setRole = (pseudo) => {
            this.tmpPseudo = pseudo;
            this.modalUpdateRole.iziModal('open');
        };

        $('#btn-validate-role').on('click', (event) => {
            event.preventDefault();

            let data = {
                role_id: $('#role_id').val()
            };
            new HttpRequest('/users/' + this.tmpPseudo + '/set-role', data, (result) => {
                location.reload();
            }, (xhr, error) => {
                new Notification("role after ajax error", this.tmpPseudo, TypeNotification.Info);
            }).post(this.modalUpdateRole.find('[name=_token]').val());
        });



        this.setGrade = (pseudo) => {
            this.tmpPseudo = pseudo;
            this.modalUpdateGrade.iziModal('open');
        };

        $('#btn-validate-grade').on('click', (event) => {
            event.preventDefault();
            let data = {
                custom_grade: $('#custom_grade').val()
            };
            new HttpRequest('/users/' + this.tmpPseudo + '/set-grade', data, (result) => {
                location.reload();
            }, (xhr, error) => {
                new Notification("role after ajax error", this.tmpPseudo, TypeNotification.Info);
            }).post(this.modalUpdateRole.find('[name=_token]').val());
        });

        this.removePicture = (pseudo) => {
            new HttpRequest('/users/' + pseudo + '/remove-picture', null, (result) => {
                new Notification("OK", "La photo de profil de " + pseudo + " a été supprimée avec succès !", TypeNotification.Success);
            }, (xhr, error) => {
                new Notification("ban after ajax error", pseudo, TypeNotification.Info);
            }).get();
        };

        // Open the modal for creating a category
        $(document).on('click', '#btn-add-category', (event) => {
            $('#category-title').val('');
            $('#category-description').val('');
            $('#category-parent-id').find('option[value="null"]').prop('selected', true);
            $('#category-min-access-level').find('option[value="0"]').prop('selected', true);

            this.updating = false;

            this.modalUpdateCategory.iziModal('open');
        });

        // Open the modal for updating a category
        $(document).on('click', '.btn-update-category', (event) => {
            this.updating = true;

            this.currentCategoryId = parseInt(event.target.getAttribute('data-category-id'));
            this.currentCategory = $(event.target);

            let t = $(event.target).attr('data-category-title');
            let p = $(event.target).attr('data-category-parent-id');
            let d = $(event.target).attr('data-category-description');
            let a = $(event.target).attr('data-category-min-access-level');

            p = p != '' ? p : "null";

            this.currentCategoryId = {
                id: event.target.getAttribute('data-category-id'),
                slug: event.target.getAttribute('data-category-slug')
            };

            $('#category-title').val(t);
            $('#category-description').val(d);
            $('#category-parent-id').find('option[value="' + p + '"]').prop('selected', true);
            $('#category-min-access-level').find('option[value="' + a + '"]').prop('selected', true);

            this.modalUpdateCategory.iziModal('open');
        });

        // Validate the update/create of a category
        $(document).on('click', '#btn-validate-update-category', (event) => {
            startLoading($('#btn-validate-update-category'), "fa-check");

            event.preventDefault();
            let url = this.updating ?
                "/categories/" + this.currentCategoryId.slug + "/update" :
                "/categories/create";

            let csrf = this.formupdateCategory.find('input[name=_token]').val();
            let cpi = $('#category-parent-id');
            let data = {
                title: $('#category-title').val(),
                description: $('#category-description').val(),
                min_access_level: $("#category-min-access-level").val(),
                parent_id: cpi.val() === "null" ? null : cpi.val()
            };

            new HttpRequest(url, data, (result) => {
                if (result === '1') {
                    new Notification("OK", "Modification/création effectuée avec succès !", TypeNotification.Success);
                    let row = $('tr[data-cat-id=' + this.currentCategoryId.id + ']');

                    if (this.updating) {
                        // On met à jour les valeurs de la ligne modifiée
                        row.find('.td-cat-title').text(<string>data.title);
                        row.find('.td-cat-access').text(<string>$("#category-min-access-level").find("option:selected").text());
                        row.find('.td-cat-descr').text(<string>data.description);
                        row.find('.td-cat-pid').text(<string>data.parent_id == null ? "Pas de parent" : <string>data.parent_id);

                        // On met à jour les data-* dans le <tr> qu'on a modifié
                        this.currentCategory.attr('data-category-title', <string>data.title);
                        this.currentCategory.attr('data-category-parent-id', <string>$('#category-parent-id').val());
                        this.currentCategory.attr('data-category-description', <string>data.description);
                        this.currentCategory.attr('data-category-min-access-level', <string>data.min_access_level);
                    } else {
                        // Création => on recharge la page
                        location.reload();

                    }

                } else {
                    new Notification("Mmmh", "La modification/création n'a pas su fonctionner :-/ Veuillez contacter Mourad.", TypeNotification.Info);
                }

                this.modalUpdateCategory.iziModal('close');
                stopLoading($('#btn-validate-update-category'), "fa-check");

            }, (xhr, error) => {
                new Notification("Mmmh", "La modification/création n'a pas su fonctionner :-/ Veuillez contacter Mourad.", TypeNotification.Info);
                this.modalUpdateCategory.iziModal('close');
                stopLoading($('#btn-validate-update-category'), "fa-check");
            }).post(csrf);
        });

        // Archive category
        $(document).on('click', '.btn-archive-category', (event) => {
            let id = event.target.getAttribute('data-category-id');
            let slug = event.target.getAttribute('data-category-slug');

            let settings: any = {
                theme: 'dark',
                icon: 'fa fa-archive',
                title: '<i class="fas fa-archive"></i> Êtes-vous sûr ?',
                message: "Désirez-vous vraiment archiver cette catégorie ? " +
                "Les discussions et messages associés ne seront plus " +
                "visibles jusqu'à ce que vous recouvrez la catégorie.",
                animateInside: true,
                layout: 2,
                maxWidth: 400,
                backgroundColor: '#ac4430',
                position: 'center', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
                progressBarColor: '#FF0000',
                buttons: [
                    [
                        '<button>Archiver</button>',
                        function (instance, toast) {
                            new HttpRequest(
                                "/categories/" + slug + "/archive", {},
                                (result) => {
                                        new Notification("OK", "Catégorie archivée avec succès !", TypeNotification.Success);
                                        let cat = $('tr[data-cat-id=' + id + '], tr[data-cat-pid=' + id + ']');
                                        cat.appendTo($("#table-cat-archived"));
                                        cat = cat.find('.btn-archive-category');
                                        cat.removeClass('btn-archive-category btn-danger')
                                            .addClass('btn-unarchive-category btn-primary')
                                            .text('Désarchiver');
                                        instance.hide({
                                            transitionOut: 'fadeOutUp'
                                        }, toast);
                                },
                                (xhr, error) => {
                                    console.log(xhr, error);
                                    new Notification("Mmmh",
                                        "Impossible d'archiver la catégorie. Veuillez contacter Mourad.",
                                        TypeNotification.Warning);
                                }
                            ).get();
                        },
                        true
                    ], // true to focus
                    [
                        '<button>Annuler</button>',
                        function (instance, toast) {
                            instance.hide({
                                transitionOut: 'fadeOutUp'
                            }, toast);
                        }
                    ]
                ],
                onOpening: function(instance, toast){
                    console.info('callback abriu!');
                },
                onClosing: function(instance, toast, closedBy){
                    console.info('closedBy: ' + closedBy); // tells if it was closed by 'drag' or 'button'
                }
            };

            iziToast.show(settings);






        });

        // Unarchive category
        $(document).on('click', '.btn-unarchive-category', (event) => {

            let id = event.target.getAttribute('data-category-id');
            let slug = event.target.getAttribute('data-category-slug');

            let settings: any = {
                theme: 'dark',
                icon: 'fa fa-archive',
                title: '<i class="fas fa-archive"></i> Êtes-vous sûr ?',
                message: "Désirez-vous vraiment archiver cette catégorie ? " +
                "Les discussions et messages associés ne seront plus " +
                "visibles jusqu'à ce que vous recouvrez la catégorie.",
                animateInside: true,
                layout: 2,
                maxWidth: 400,
                backgroundColor: '#5a88ac',
                position: 'center', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
                progressBarColor: '#6472ff',
                buttons: [
                    [
                        '<button>Désarchiver</button>',
                        function (instance, toast) {
                            new HttpRequest(
                                "/categories/" + slug + "/unarchive", {},
                                (result) => {
                                    if (result === '1') {
                                        new Notification("OK", "Catégorie désarchivée avec succès !", TypeNotification.Success);
                                        let cat = $('tr[data-cat-id=' + id + ']');
                                        cat.appendTo($("#table-cat-unarchived"));
                                        cat = cat.find('.btn-unarchive-category');
                                        cat.removeClass('btn-unarchive-category btn-primary')
                                            .addClass('btn-archive-category btn-danger')
                                            .text('Archiver');
                                        instance.hide({transitionOut: 'fadeOutUp'}, toast);
                                    }
                                    else if (result === '-1') {
                                        new Notification(
                                            "Erreur",
                                            "Vous devez d'abord désarchiver la catégorie parent " +
                                            "de la catégorie que vous avez sélectionné !",
                                            TypeNotification.Warning
                                        );
                                        instance.hide({transitionOut: 'fadeOutUp'}, toast);
                                    } else {
                                        new Notification(
                                            "Mmmmh",
                                            "Impossible de désarchiver la catégorie. Veuillez contacter Mourad.",
                                            TypeNotification.Info
                                        );
                                        instance.hide({transitionOut: 'fadeOutUp'}, toast);
                                    }
                                },
                                (xhr, error) => {
                                    console.log(xhr, error);
                                    new Notification("Mmmh",
                                        "Impossible de désarchiver la catégorie. Veuillez contacter Mourad.",
                                        TypeNotification.Warning);
                                }
                            ).get();
                        },
                        true
                    ], // true to focus
                    [
                        '<button>Annuler</button>',
                        function (instance, toast) {
                            instance.hide({
                                transitionOut: 'fadeOutUp'
                            }, toast);
                        }
                    ]
                ],
                onOpening: function(instance, toast){
                    console.info('callback abriu!');
                },
                onClosing: function(instance, toast, closedBy){
                    console.info('closedBy: ' + closedBy); // tells if it was closed by 'drag' or 'button'
                }
            };

            iziToast.show(settings);
        });

        $(document).on('click', '.action-user', (event) => {
            let action = event.target.getAttribute('data-action');
            let pseudoUser = $(event.target.parentNode).attr('data-user-pseudo');
            switch (action)
            {
                case 'ban': this.ban(pseudoUser); break;
                case 'unban': this.unban(pseudoUser); break;
                case 'activate': this.activate(pseudoUser); break;
                case 'deactivate': this.deactivate(pseudoUser); break;
                case 'set-role': this.setRole(pseudoUser); break;
                case 'set-grade': this.setGrade(pseudoUser); break;
                case 'remove-picture': this.removePicture(pseudoUser); break;
                case 'get-profile': location.href = event.target.getAttribute('data-url'); break;
                default: new Notification("Ouupps...", "Commande inconnue.", TypeNotification.Warning); break;
            }
        });
    }
}
