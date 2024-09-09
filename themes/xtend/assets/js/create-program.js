$('.ui.file.input').find('input:text, .ui.button')
  .on('click', function(e) {
    $(e.target).parent().find('input:file').click();
  })
;

$(document).ready(function(){
  $('select').each( function() {
      $(this).val( $(this).find("option[selected]").val() );
  });


  $('#tags_dropdown')
    .dropdown({
      allowAdditions: true
    })
  ;

  $('#postas_dropdown')
    .dropdown({
      maxSelections: 1
    })
  ;
  checkPostTo();
  checkPostAs();
});

$('input:file', '.ui.file.input')
  .on('change', function(e) {
    var file = $(e.target);
    var name = '';

    for (var i=0; i<e.target.files.length; i++) {
      name += e.target.files[i].name + ', ';
    }
    // remove trailing ","
    name = name.replace(/,\s*$/, '');

        $('input:text', file.parent()).val(name);
  })
;

$('.ui.grid form')
  .form({
    on: 'blur',
    fields: {
      title: {
        identifier  : 'title',
        rules: [
          {
            type   : 'empty',
            prompt : 'Please enter title'
          }
        ]
      },
      featured_image: {
        identifier  : 'featured_image',
        rules: [
          {
            type   : 'empty',
            prompt : 'Please add feature image'
          }
        ]
      },
      postas: {
        identifier  : 'postas',
        rules: [
          {
            type   : 'empty',
            prompt : 'Please select a dropdown value'
          }
        ]
      },
      category: {
        identifier  : 'category_dropdown',
        rules: [
          {
            type   : 'empty',
            prompt : 'Please Select category'
          }
        ]
      },
      tag: {
        identifier  : 'tags',
        rules: [
          {
            type   : 'empty',
            prompt : 'Please add min one tag'
          }
        ]
      },
      postasname: {
        identifier  : 'postas_dropdown',
        rules: [
          {
            type   : 'empty',
            prompt : 'Please enter Poster name'
          }
        ]
      },
      postto: {
        identifier  : 'postto',
        rules: [
          {
            type   : 'empty',
            prompt : 'Please select target post'
          }
        ]
      },
      posttoname: {
        identifier  : 'postto_dropdown',
        rules: [
          {
            type   : 'empty',
            prompt : 'Please enter target name'
          }
        ]
      }
    }
  })
;