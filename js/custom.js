$('label').click(function() {  
    if ($('#btnsubmitrating').is(':disabled')) {  
        $('#btnsubmitrating').removeAttr('disabled');  
    } else {  
        $('#btnsubmitrating').attr('disabled', 'disabled');  
    }  
});  



