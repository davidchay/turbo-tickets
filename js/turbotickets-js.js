jQuery(function($){
    $('#habilitar').on('change', function(){
        if($(this).attr('checked')){
            $('#status').removeAttr('disabled');
            $('#submitstatus').removeAttr('disabled');
        }else{
            $('#status').attr('disabled', 'disabled');
            $('#submitstatus').attr('disabled','disabled');
        }
    });

    
    $('.image-link').magnificPopup({type:'image'});

    
});

