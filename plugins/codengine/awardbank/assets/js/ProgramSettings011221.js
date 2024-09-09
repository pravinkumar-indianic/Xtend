forms = {
    'dashboardsettingsform' : 'onDashboardSettingsUpdate',
    'loginpageform' : 'onLoginPageUpdate',
}

$.each( forms, function( key, value ) {
    $('body').on('submit','#'+key, function(e) {
        e.preventDefault();
        var inputs = $('#' + key + ' :input');
        var data = {};
        if (inputs && data) {
            inputs.each(function () {
                data[this.name] = $(this).val();
            });
            requestFactory('ProgramSettings::' + value, data, ['#html2target']);
        }
    });
});
