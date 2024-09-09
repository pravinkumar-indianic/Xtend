$('body').on('click', '.change-program-option', function() {
    var programid = $(this).attr('data-id');
    data = {
      'programid' : programid,  
    }
    requestFactory('SwitchProgram::onSwitchProgram', data, ['.pusher '], 'refresh');
});