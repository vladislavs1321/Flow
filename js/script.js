/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
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
    
/** LOOK AT THIS **/
    
    // Add event on hidden link to initialize pageslider open/close event
    // Unfortunatly we must set modal true to plugin!
    $('#pageslider-initiator').pageslide({ direction: "left", modal: true});
    
    // When click on orange images to login or register
    $('.show-pageslider').on('click', function(e) {
        e.preventDefault();
        
        // Get attribute 'data-open-slide' from clicked orange button
        // Attribute can have values - 'login' or 'register' and say us which div in pageslider we should display finded by 'data-slider' attribute
        showDiv = $(this).data('open-slide');
        
        if (false === $('#pageslide').hasClass('opened')) {
            
            // This happend when pageslider closed, and we press any orange buttons
            closePageslider(showDiv);
            
        } else {
            
            if ($('#pageslide').data('modal-active') === showDiv) {
            
                // This happend when pageslider is currently opened, and pageslider 'data-modal-active' attribute match pressed button 'data-open-slide' attribute
                // We must hide pageslider and hide displayed content in it!
                showPageslider(showDiv);
            } else {
                
                // This happend when pageslider is currently opened, and pageslider 'data-modal-active' attribute NOT match pressed button 'data-open-slide' attribute
                // We must hide pageslider and hide displayed content in it then open again pageslider and show other form
                switchPageslider(showDiv);
            }
        }
    });
    
/* Reusable functions */
    
    function showPageslider(showDiv) {
        $('#pageslider-initiator').trigger('click');
            $('#pageslide')
                .removeClass('opened')
                .removeAttr('data-modal-active')
                .find('div[data-slider="' + showDiv + '"]')
                    .fadeOut(1000);
    }
    
    function closePageslider(showDiv) {
        $('#pageslider-initiator').trigger('click');
            $('#pageslide')
                .addClass('opened')
                .data('modal-active', showDiv)
                .find('div[data-slider="' + showDiv + '"]')
                    .fadeIn(1000);
    }
    
    function switchPageslider(showDiv) {
        $('#pageslider-initiator').trigger('click');
            $('#pageslide')
                .data('modal-active', showDiv)
                .find('div[data-slider="' + showDiv + '"]')
                .siblings('.sub-modal')
                    .fadeOut(300, function() {
                        // Ckick again on hidden btn to show again pageslider with other content
                        // Important do it after firs click is triggered with some timeout!!
                        $('#pageslider-initiator').trigger('click');
                        $('#pageslide')
                            .find('div[data-slider="' + showDiv + '"]')
                            .fadeIn(1000);
                    });
    }
});
