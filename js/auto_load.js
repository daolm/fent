function ClientPull() {
    this.timeout = window.TIMEOUT;   
    this.is_started = false;
}

ClientPull.prototype.start = function() {
    this.is_started = true;
    this.interval = setInterval(function() {
        getNoti();        
    }, this.timeout); 
};

ClientPull.prototype.end = function() {
    clearInterval(this.interval);
    this.is_started = false;
};

function getNoti(){
    var url = window.location.protocol + '//' + window.location.host + window.location.pathname + '?r=notification/getNotifications';
    var html = '';
    $.ajax({        
        type: 'GET',
        url: url,
        success: function(msg){
            var obj = jQuery.parseJSON(msg);            
            $.each(obj, function(index, value) {                    
                addNewNotification(value);
            });          
        }
    });
}

function addNewNotification(value) {
    if ($('#notification_' + value['notification_id']).length == 0) {
        var html = returnHtml(value);                    
        $('#notification').prepend(html);
        $('#notification_' + value['notification_id']).show(window.FADING_DURATION, function(){
            addDeleteListener('#i_' + value['notification_id']);
        });
    }
}

function returnHtml(value){
    var notice_class = (value['status'] == 'rejected') ? 'danger' : 'success';
    var html = '<div class="row ' + notice_class + ' alert notification" id="notification_' + value['notification_id'] + '" hidden="hidden">';
    if (value['status'] != 'waiting') {
        html += 'Admin ' + value['status'] + ' your';
    } else {
        html += 'You have a new ' + value['status'];
    }
    html += '<a href="' + createUrl('request', value['request_id']) + '"> request</a>';
    html += ' at time: ' + value['time'];
    html += '<i id="i_' + value['notification_id'] + '"class="icon-cancel-circled delete_notification" notification_id="' + value['notification_id'] + '"></i>';
    html += '</div>';
    return html;
}