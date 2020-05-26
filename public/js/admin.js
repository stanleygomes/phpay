$(document).ready(function () {
    $('.summernote').summernote({
        placeholder: '',
        tabsize: 2,
        height: 150,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    $('.upload-image').fileinput({
        showUpload: false,
        removeClass: 'hidden',
        showPreview: false,
        msgPlaceholder: 'Arquivo selecionado...',
        browseLabel: 'Escolher',
        allowedFileTypes: 'image',
        msgInvalidFileType: 'Tipo de arquivo não suportado'
    });
});
