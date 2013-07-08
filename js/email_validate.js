$(function(){
    $('form').submit(function(event){
        var email = $('#email').val();
        if ($.trim(email).length == 0 || !isEmail(email)) {
            alert('Please enter valid email');
            event.preventDefault();
        }
    });
});

function isEmail(email){
  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}