$(function(){
   $('#features, #contact, #about').click(function(){
       var content_id = '#' + $(this).attr('id') + '_content';       
       $(content_id).toggle(window.FADING_DURATION);
   }); 
});