import iziModal from 'izimodal/js/iziModal';
import {HttpRequest, Notification, TypeNotification, startLoading, stopLoading} from "./core"
import 'izitoast'

export class Administration {

    private btnArchiveCategory: JQuery;
    private btnUpdateCategory: JQuery;
    private btnValidateUpdateCategory: JQuery;
    private formupdateCategory: JQuery;
    private currentCategory: JQuery;

    private modalUpdateCategory: any;
    private currentCategoryId: any;
    private updating:boolean;

    constructor() {

        this.btnUpdateCategory   = $('.btn-update-category');
        this.btnArchiveCategory  = $('.btn-archive-category');
        this.modalUpdateCategory = $('#modal-update-category');
        this.formupdateCategory  = $('#form-update-category');
        this.btnValidateUpdateCategory = $('#btn-validate-update-category');
        this.currentCategoryId = {};

        this.updating = false;

        this.modalUpdateCategory.iziModal({
            title: '<i class="fas fa-pencil-alt"></i> Modifier la catégorie',
            closeOnEscape: true,
            closeButton: true,
            headerColor: '#ff9269'
        });

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
    }
}
