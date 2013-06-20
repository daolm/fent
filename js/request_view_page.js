$(function(){ 
    $('.reject_request_btn').click(function(){
        var request_id = $(this).attr('request_id');
        var value = $(this).attr('value');
        rejectRequest(request_id, value);
    });
    $('.accept_request_btn').click(function(){
        var request_id = $(this).attr('request_id');
        var value = $(this).attr('value');
        acceptRequest(request_id, value);
    });
});

function acceptRequest(request_id, value) {
    var url = window.location.protocol + '//' + window.location.host + window.location.pathname + '?r=request/rejectOrAccept';                
    $.ajax({
        type: 'POST',
        url: url,
        data: {             
            request_id: request_id,
            value: value
        }
    }).success(function() {     
            $('#status').fadeOut(window.FADING_DURATION, function(){
                $('#status').html('Un-expired');
                $('#status').fadeIn(window.FADING_DURATION);
            });
            $('#start_time').html(getDate());
            $('#button_group').hide(window.FADING_DURATION);
        }).fail(function() {            
            alert('Fail !');
        });
}

function rejectRequest(request_id, value) {
    var url = window.location.protocol + '//' + window.location.host + window.location.pathname + '?r=request/rejectOrAccept';                
    $.ajax({
        type: 'POST',
        url: url,
        data: {             
            request_id: request_id,
            value: value
        }
    }).success(function() { 
            $('#status').fadeOut(window.FADING_DURATION, function(){
                $('#status').html('Rejected');
                $('#status').fadeIn(window.FADING_DURATION);
            });
            $('#button_group').hide(window.FADING_DURATION);
        }).fail(function() {            
            alert('Fail !');
        });
}

function getDate() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    if(dd < 10){
        dd = '0' + dd
    } 
    if(mm<10){
        mm='0'+mm
    } 
    return dd+'/'+mm+'/'+yyyy;
}

$(function(){
    $('.finish_request_btn').click(function(){
        var request_id = $(this).attr('request_id');
        finishRequest(request_id);
    });
});

function finishRequest(request_id) {
    var url = window.location.protocol + '//' + window.location.host + window.location.pathname + '?r=request/finish';                
    $.ajax({
        type: 'POST',
        url: url,
        data: {             
            request_id: request_id,
        }
    }).success(function() {               
            $('#status').fadeOut(window.FADING_DURATION, function(){
                $('#status').html('Finished');
                $('#status').fadeIn(window.FADING_DURATION);
            });
            $('#end_time').html(getDate());
            $('#finish_button').hide(window.FADING_DURATION);
        }).fail(function() {            
            alert('Fail !');
        });
}