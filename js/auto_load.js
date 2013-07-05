$(function(){
    var cp = new ClientPull();
    cp.start();    
});

function ClientPull() {
    this.timeout = timeout;    
}

ClientPull.prototype.start = function() {
    this.interval = setInterval(function() {
        getNoti();
    }, this.timeout); 
};

ClientPull.prototype.end = function() {
    clearInterval(this.interval);
};

function getNoti(){
    var url = window.location.protocol + '//' + window.location.host + window.location.pathname + '?r=notification/getNotifications';
    var html = '';
    $.ajax({        
        type: 'GET',
        url: url,
        success: function(msg){
            var obj = jQuery.parseJSON(msg);
            $.each(obj, function(notification_id, value) {                
                if ($('#notification_' + notification_id).length == 0) {
                    html = returnHtml(notification_id, value);                    
                    $('#notification').prepend(html);
                    $('#notification_' + notification_id).show(window.FADING_DURATION, function(){
                        addDeleteListener('#i_' + notification_id);
                    });
                }
            });          
        }
    });
}

function returnHtml(notification_id, value){
    var notice_class = (value['status'] == 'rejected') ? 'danger' : 'success';
    var html = '<div class="row ' + notice_class + ' alert notification" id="notification_' + notification_id + '" hidden="hidden">';
    if (value['status'] != 'waiting') {
        html += 'Admin ' + value['status'] + ' your';
    } else {
        html += 'You have a new ' + value['status'];
    }
    html += '<a href="' + createUrl('request', value['request_id']) + '"> request</a>';
    html += ' at time: ' + value['time'];
    html += '<i id="i_' + notification_id + '"class="icon-cancel-circled delete_notification" notification_id="' + notification_id + '"></i>';
    html += '</div>';
    return html;
}