import * as $ from 'jquery'
import {HttpRequest, Notification, TypeNotification} from "./core"
import * as katex from 'katex'

export class Discussion {
    private formUpdateMessage: JQuery;
    private containerUpdateMessage: JQuery;
    private updateMessages: JQuery;
    private buttonUpdateMessage: JQuery;
    private tempData: any;
    private htmlKatexToFormula:Function;
    private baseUrl:string;
    private currentBtn: JQuery;


    constructor() {
        this.formUpdateMessage      = $('#form-update-message');
        this.containerUpdateMessage = $('#container-update-message');
        this.updateMessages         = $('.link-update-message');
        this.buttonUpdateMessage    = $('#submit-update-message');
        this.baseUrl                = this.formUpdateMessage.attr('data-base-url');

        this.updateMessages.on('click', (e) => {
            e.preventDefault();

            this.currentBtn = $(e.target);

            if (this.containerUpdateMessage.attr('data-currently-updating') === 'true')
                return;
            this.containerUpdateMessage.attr('data-currently-updating', 'true');

            let containerPost = $('#content-' + this.currentBtn.attr('data-post-id'));
            let containerTextareaPost = $('#content-textarea-' + this.currentBtn.attr('data-post-id'));
            // On save le post temporairement.
            this.tempData = containerPost.html();
            // Va modifier l'html
            this.htmlKatexToFormula(containerPost);

            // avant de move ckeditor je dois le détruire sinon crash
            if (CKEDITOR.instances['update-message'])
                CKEDITOR.instances['update-message'].destroy();

            this.containerUpdateMessage.appendTo(containerTextareaPost);


            // Puis on le recréé
            CKEDITOR.replace('update-message');
            CKEDITOR.instances['update-message'].setData(containerPost.html());

            // Puis vider le contenu du post temporairement :
            containerPost.empty();
            this.containerUpdateMessage.toggleClass('d-none');
        });

        this.htmlKatexToFormula = function(container:any) {
            let formulas = container.find('span.katex');

            for(let i = 0; i < formulas.length; i++) {
                let originalFormula = $(formulas[i]).find('annotation').text();

                if ($(formulas[i].parentNode).hasClass('katex-display'))
                    formulas[i] = '\\[' + originalFormula + '\\]';
                else
                    formulas[i] = '\\(' + originalFormula + '\\)';
            }

            container.find('.katex').each(function (key:number, val:any) {
                $(val).replaceWith(formulas[key]);
            });
        };

        this.buttonUpdateMessage.on('click', (e) => {
            e.preventDefault();

            let postId        = this.currentBtn.attr('data-post-id');
            let url           = this.baseUrl + '/' + postId;
            let data          = { 'content': CKEDITOR.instances['update-message'].getData() };
            let csrfToken     = this.formUpdateMessage.find('input[name=_token]').val();
            let containerPost = $('#content-' + postId);
            let containerTextareaPost = $('#content-textarea-' + postId);

            let success = (result:any) => {
                if (result === '1') {
                    new Notification('OK', 'Le message a été updaté !', TypeNotification.Success);
                    containerPost.html(data.content);
                    // avant de move ckeditor je dois le détruire sinon crash
                    if (CKEDITOR.instances['update-message'])
                        CKEDITOR.instances['update-message'].destroy();
                    this.containerUpdateMessage.appendTo($('#wrapper-update-message'));
                    this.containerUpdateMessage.toggleClass('d-none');
                }
                else
                    new Notification('Mmmmh... ', 'Y a une couille dans le potager.', TypeNotification.Info);

                this.containerUpdateMessage.attr('data-currently-updating', 'false');
                renderMathInElement(containerPost[0], null);
            };

            let error = (xhr:any, error:any) => {
                new Notification('Erreur !', 'Je ne sais pas pourquoi :-/', TypeNotification.Warning);
                console.log('errrooorrr', xhr, error);
            };

            new HttpRequest(url, data, success, error).post(csrfToken);
        });

    }

}


// juste pour katex bordel...
const findEndOfMath = function(delimiter:any, text:any, startIndex:any) {
    // Adapted from
    // https://github.com/Khan/perseus/blob/master/src/perseus-markdown.jsx
    let index = startIndex;
    let braceLevel = 0;

    const delimLength = delimiter.length;

    while (index < text.length) {
        const character = text[index];

        if (braceLevel <= 0 &&
            text.slice(index, index + delimLength) === delimiter) {
            return index;
        } else if (character === "\\") {
            index++;
        } else if (character === "{") {
            braceLevel++;
        } else if (character === "}") {
            braceLevel--;
        }

        index++;
    }

    return -1;
};

const splitAtDelimiters = function(startData:any, leftDelim:any, rightDelim:any, display:any) {
    const finalData = [];

    for (let i = 0; i < startData.length; i++) {
        if (startData[i].type === "text") {
            const text = startData[i].data;

            let lookingForLeft = true;
            let currIndex = 0;
            let nextIndex;

            nextIndex = text.indexOf(leftDelim);
            if (nextIndex !== -1) {
                currIndex = nextIndex;
                finalData.push({
                    type: "text",
                    data: text.slice(0, currIndex),
                });
                lookingForLeft = false;
            }

            while (true) {
                if (lookingForLeft) {
                    nextIndex = text.indexOf(leftDelim, currIndex);
                    if (nextIndex === -1) {
                        break;
                    }

                    finalData.push({
                        type: "text",
                        data: text.slice(currIndex, nextIndex),
                    });

                    currIndex = nextIndex;
                } else {
                    nextIndex = findEndOfMath(
                        rightDelim,
                        text,
                        currIndex + leftDelim.length);
                    if (nextIndex === -1) {
                        break;
                    }

                    finalData.push({
                        type: "math",
                        data: text.slice(
                            currIndex + leftDelim.length,
                            nextIndex),
                        rawData: text.slice(
                            currIndex,
                            nextIndex + rightDelim.length),
                        display: display,
                    });

                    currIndex = nextIndex + rightDelim.length;
                }

                lookingForLeft = !lookingForLeft;
            }

            finalData.push({
                type: "text",
                data: text.slice(currIndex),
            });
        } else {
            finalData.push(startData[i]);
        }
    }

    return finalData;
};

const splitWithDelimiters = function(text:any, delimiters:any) {
    let data:any = [{
        type: <any>"text", data: <any>text}];
    for (let i = 0; i < delimiters.length; i++) {
        const delimiter = delimiters[i];
        data = splitAtDelimiters(
            data, delimiter.left, delimiter.right,
            delimiter.display || false);
    }
    return data;
};

/* Note: optionsCopy is mutated by this method. If it is ever exposed in the
 * API, we should copy it before mutating.
 */
const renderMathInText = function(text:any, optionsCopy:any) {
    const data = splitWithDelimiters(text, optionsCopy.delimiters);
    const fragment = document.createDocumentFragment();

    for (let i = 0; i < data.length; i++) {
        if (data[i].type === "text") {

            fragment.appendChild(document.createTextNode(data[i].data));
        } else {
            const span = document.createElement("span");
            const math = data[i].data;
            // Override any display mode defined in the settings with that
            // defined by the text itself
            optionsCopy.displayMode = data[i].display;
            try {
                katex.render(math, span, optionsCopy);
            } catch (e) {
                if (!(e instanceof katex.ParseError)) {
                    throw e;
                }
                optionsCopy.errorCallback(
                    "KaTeX auto-render: Failed to parse `" + data[i].data +
                    "` with ",
                    e
                );
                fragment.appendChild(document.createTextNode(data[i].rawData));
                continue;
            }
            fragment.appendChild(span);
        }
    }

    return fragment;
};

const renderElem = function(elem:any, optionsCopy:any) {
    for (let i = 0; i < elem.childNodes.length; i++) {
        const childNode = elem.childNodes[i];
        if (childNode.nodeType === 3) {
            // Text node
            const frag = renderMathInText(childNode.textContent, optionsCopy);
            i += frag.childNodes.length - 1;
            elem.replaceChild(frag, childNode);
        } else if (childNode.nodeType === 1) {
            // Element node
            const shouldRender = optionsCopy.ignoredTags.indexOf(
                childNode.nodeName.toLowerCase()) === -1;

            if (shouldRender) {
                renderElem(childNode, optionsCopy);
            }
        }
        // Otherwise, it's something else, and ignore it.
    }
};

const defaultAutoRenderOptions = {
    delimiters: [
        {left: "$$", right: "$$", display: true},
        {left: "\\[", right: "\\]", display: true},
        {left: "\\(", right: "\\)", display: false},
        // LaTeX uses this, but it ruins the display of normal `$` in text:
        // {left: "$", right: "$", display: false},
    ],

    ignoredTags: [
        "script", "noscript", "style", "textarea", "pre", "code",
    ],

    errorCallback: function(msg:any, err:any) {
        console.error(msg, err);
    },
};

const renderMathInElement = function(elem:any, options:any) {
    if (!elem) {
        throw new Error("No element provided to render");
    }

    const optionsCopy = Object.assign({}, defaultAutoRenderOptions, options);
    renderElem(elem, optionsCopy);
};