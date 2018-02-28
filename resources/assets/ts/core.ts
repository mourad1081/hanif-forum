import * as $ from "jquery"
import 'izitoast'

export class HttpRequest {

    private url:string;
    private success: any;
    private error: any;
    private data: object;
    public get: Function;
    public post: Function;

    /**
     *
     * @param {string} url
     * @param {Object} data
     * @param success
     * @param error
     */
    constructor(url:string, data:object, success: any, error: any) {
        this.url = url;
        this.success = success;
        this.error = error;
        this.data = data;

        this.get = () => {
            $.ajax({
                url:      this.url,
                type:     'get',
                success:  this.success,
                error:    this.error
            });
        };

        this.post = (csrfToken: string) => {
            $.ajax({
                url:      this.url,
                headers: { "X-CSRF-TOKEN": csrfToken },
                type:    'post',
                data:    this.data,
                success: this.success,
                error:   this.error
            });
        }
    }
}

/**
 * Define different types of notification.
 */
export enum TypeNotification {
    Info = 1,
    Error,
    Warning,
    Success,
}

export class Notification {

    constructor(title:string, message:string, type:TypeNotification) {

        let settings: IziToastSettings = {
            title: title,
            message: message,
            position: 'topRight',
        };


        switch(type) {
            case TypeNotification.Info:    iziToast.info(settings); break;
            case TypeNotification.Success: iziToast.success(settings); break;
            case TypeNotification.Warning: iziToast.warning(settings); break;
            case TypeNotification.Error:   iziToast.error(settings); break;
        }
    }
}

/**
 * Place un spinner loading sur l'élément reçu en paramètre à la place de l'icone déjà en place
 * @param $HTMLElement
 * @param classToRemove
 */
export let startLoading = function ($HTMLElement:JQuery, classToRemove:string)
{
    // Avec fontawesome 5, c'est rendered en SVG mtn
    $HTMLElement.find('svg')
                .removeClass(classToRemove)
                .addClass('fa-circle-notch fa-spin');

    $HTMLElement.addClass('disabled');
};

/**
 * Place un spinner loading sur l'élément reçu en paramètre à la place de l'icone déjà en place
 * @param $HTMLElement
 * @param classToAdd
 */
export let stopLoading = function ($HTMLElement:JQuery, classToAdd:string)
{
    $HTMLElement.find('svg')
                .addClass(classToAdd)
                .removeClass('fa-circle-notch fa-spin');

    $HTMLElement.removeClass('disabled');
};

