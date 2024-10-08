﻿//Editor page hmtl editor triggers
'use strict'
tinymce.remove();
tinymce.init({
    selector: '.tinymceeditor',
    height: 250,
    plugins: [
      'advlist autolink lists link image charmap print preview anchor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table contextmenu paste code'
    ],
    toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
    content_css: [
      '/themes/xtend/assets/css/codepen.min.css'
    ]
});

