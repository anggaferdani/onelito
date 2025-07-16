<script src="https://cdn.tiny.cloud/1/chspd9x2h2e3sybb8cis7ofo1twiw6ear12z9rzf67ynayrr/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
    tinymce.init({
        selector: '.tinymce',
        height: 500,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'lineheight',
            'paste'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | link image media table | ' +
            'removeformat | code fullscreen preview | searchreplace | ' +
            'insertdatetime charmap anchor | lineheight | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',

        paste_as_text: false,
        paste_data_images: true,
        paste_enable_default_filters: false,
        paste_word_valid_elements: '*[*]',
        paste_webkit_styles: 'all',
        paste_merge_formats: false,

        content_css: false
    });
</script>
