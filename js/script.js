/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
$(".show-sign-in").pageslide({ direction: "left"});    
//          ***    SUBMITTING FORM  ***

    $('#entr').on('click', function(e) {
        e.preventDefault();
        
        that = this;
        
        $.post(
            $(that).parents('form').attr('action'),
            $(that).parents('form').serialize(),
            function(responce){
                if (true === responce.success) {
                    $('.login span>span').html(responce.username);
                } else{
                        $('#pageslide').css({
                            boxShadow :  "rgba(255, 0, 0, 0) 0px 0px 60px 5px inset"
                        });
                        $('#pageslide').animate({
                            boxShadow :  "rgba(255, 0, 0, 1) 0px 0px 60px 5px inset"
                        },500).addClass('errored');
                        $('.error-message').html(responce.error.message);
                }
            },
            'json'
        );
    });
    
    $('.form-login').on('focus',function(){
        if ($('#pageslide').hasClass('errored')) {
            $('.error-message').empty();
            $('#pageslide').animate({
                boxShadow :  '#ff0000 0 0 0'
                },500, function() {
                    $(this).css({
                        boxShadow : "#222 0 0 5px 5px inset" 
                    });
                }
            ).removeClass('errored');
        }
    });
    
    $('a.reg .show-sign-in').on('click',function(){
        alert('ff');
        
        
        $.pageslide.close();
    
        alert('ff');
     
    });
});
