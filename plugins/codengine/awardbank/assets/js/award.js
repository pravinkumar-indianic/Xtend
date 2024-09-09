$(document).ready(function(){
    var totalawards = $('#totalawards').val();

    if (totalawards > 0){

        for (var i = 1; i <= totalawards.length; ++i) {
            startCountdown("countdown_" + i + "_label", $('#countdown_' + i).data('enddate') );    
        }
    }

});

function startCountdown(divid, enddate){
    var countDownDate = new Date(enddate).getTime();
    var x = setInterval(function() {
        var now = new Date().getTime();
        var distance = countDownDate - now;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById(divid).innerHTML = days + "d " + hours + "h "
+ minutes + "m " + seconds + "s ";
        if (distance < 0) {
            clearInterval(x);
            document.getElementById(divid).innerHTML = " VOTES CLOSED";
          }
    }, 1000);
}