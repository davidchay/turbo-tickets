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

    $('#eliminar_ticket').on('click', function(){
        
        var eliminar = confirm("¿Esta realmente seguro de eliminar el ticket?");
        if(eliminar){
            console.log('se elimino');
        }
        else{
            console.log('no se elimino nada');
        }
        
    });

    $('.image-link').magnificPopup({type:'image'});

    
    jQuery('#turboticket-update').attr('enctype','multipart/form-data');
    jQuery('#turboticket-update').attr('encoding', 'multipart/form-data');
});

