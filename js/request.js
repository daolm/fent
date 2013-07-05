$(function(){ 
    $('.reject_request').click(function(){
        var request_id = $(this).attr('request_id');
        var value = $(this).attr('value');
        rejectRequestAtHomepage(request_id, value);
    });
});

$(function(){ 
    $('.accept_request').click(function(){
        var request_id = $(this).attr('request_id');
        var value = $(this).attr('value');
        var request_end_time = $('#' + request_id).attr('placeholder');
        acceptRequestAtHomepage(request_id, value, request_end_time);
    });
});

function rejectRequestAtHomepage(request_id, value) {
    var url = window.location.protocol + '//' + window.location.host + window.location.pathname + '?r=request/rejectOrAccept';
    $.ajax({
        type: 'POST',
        url: url,
        data: {             
            request_id: request_id,
            value: value
        }
    }).success(function() {
            $('#request_' + request_id).hide(window.FADING_DURATION);
        }).fail(function() {            
            alert('Fail !');
        });
}

function acceptRequestAtHomepage(request_id, value, request_end_time) {
    var url = window.location.protocol + '//' + window.location.host + window.location.pathname + '?r=request/rejectOrAccept';                
    $.ajax({
        type: 'POST',
        url: url,
        data: {             
            request_id: request_id,
            value: value
        }
    }).success(function() {
        if (request_end_time !== 'No time given') {
            var html = newUnexpiredRequests(request_id);
            $('#unexpired_requests').append(html);
        }
        $('#request_' + request_id).hide(window.FADING_DURATION, function() {
            $(this).remove();
            initDatePiker(request_id);
            addFinishListener(request_id);
        });
        }).fail(function() {
            alert('Fail !');
        });
}

function newUnexpiredRequests(request_id) {
    var profile_id = $('#request_' + request_id).attr('profile_id');
    var profile_link = createUrl('profile', profile_id);
    var username = $('#request_' + request_id).attr('username');
    var device_id = $('#request_' + request_id).attr('device_id');
    var device_link = createUrl('device', device_id);
    var device_name = $('#request_' + request_id).attr('device_name');
    var request_link = createUrl('request', request_id);
    var borrowing_start_time = getDateFormat(new Date());
    var request_end_time = $('#' + request_id).attr('placeholder');
    var days_left = dateDiff(borrowing_start_time, request_end_time);
    
    var html = '<div class="row" id="request_' + request_id +
            '"><div class="two columns crop"><a href="' + profile_link + '">' + username + '</a></div>';
    html += '<div class="two columns crop"><a href="' + device_link + '">' + device_name + '</a></div>';
    html += '<div class="two columns">' + borrowing_start_time + '</div>';
    html += '<div class="two columns"><input request_id="' + request_id +
           '" id="date_end_' + request_id + '" request_start_time="' + borrowing_start_time +
           '" placeholder="' + request_end_time + '" readonly="readonly" style="width:100%" type="text" name="' +
           request_id + '" id="' + request_id + '"></div>';
    html += '<div class="one columns">' + days_left + '</div>';
    html += '<div class="two columns ">' + '<span class="small primary btn"><a href="' +
            request_link + '">View more</a></span></div>';
    html += '<div class="one columns"><span class="small warning btn"><input id="finish_request_' +
            request_id + '" request_id="' + request_id + '" type="button" value="Finish"></span></div>';
    html += '</div>';
    return html;
}

function dateDiff(start_date, end_date, format) { //calculate date diff with date's format is dd/mm/yyyy or dd-mm-yyyy
    if (end_date === 'No time given') {
        return '';
    }
    if (format === undefined) {
        format = '/';
    }
    var start = start_date.split(format);
    var end = end_date.split(format);
    var s = new Date(start[2], start[1] - 1, start[0]);
    var e = new Date(end[2], end[1] - 1, end[0]);
    var days = (e -s)/1000/60/60/24;
    return days + ' days';
}

$(function(){
    addFinishListener();
});

function addFinishListener(id){
    if (id === undefined) { 
        $('.finish_request').click(function(){
            var request_id = $(this).attr('request_id');
            finishRequestAtHome(request_id);
        });
    } else {
        $('#finish_request_' + id).click(function(){
            finishRequestAtHome(id);
        });
    }
}

function finishRequestAtHome(request_id) {
    var url = window.location.protocol + '//' + window.location.host + window.location.pathname + '?r=request/finish';                
    $.ajax({
        type: 'POST',
        url: url,
        data: {             
            request_id: request_id
        }
    }).success(function() {               
            $('#request_' + request_id).hide(window.FADING_DURATION);
        }).fail(function() {            
            alert('Fail !');
        });
}