$('body').on('click', '.team-option', function() {

    var teamID = $(this).attr('data-id');

    if(teamID){

        var target = 'access::onTeamUpdate';

        $.request(target, {
            data: {
            	'teamID' : teamID, 
            },

            redirect: '/dashboard',

            error: function(data){
              throw data;
            },
        });

    }

});

$('body').on('click', '.program-option', function() {

    console.log('test');

    var programID = $(this).attr('data-id');

    if(programID){

        var target = 'access::onProgramUpdate';

        $.request(target, {
            data: {
                'programID' : programID, 
            },

            redirect: '/dashboard',

            error: function(data){
              throw data;
            },
        });

    }

});