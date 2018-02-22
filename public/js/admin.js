$('.secondary.menu .item').tab();
// Replace the <textarea id="content"> with a CKEditor
// instance, using default configuration.
CKEDITOR.replace('content');

var title = $("#article-title");
var content = $("#article-content");

$('#btn-save-article').click(function (event) {
    event.preventDefault();
    event.stopPropagation();

    var article = {
        title: title.val(),
        content: CKEDITOR.instances['article-content'].getData(),
        categories: [2,3]
    };

    sendTo('posts', PUT, article, function (data) {
        console.log(data);
    }, function (xhr, error) {
        console.log(xhr, "----------", error);
    })
});