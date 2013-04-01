/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
    //          ***    SUBMITTING AUTH FORM  ***
    $('#entr, .sign-up').on('click', function(e) {
        e.preventDefault();
        that = this;
        
        $.post(
            $(that).parents('form').attr('action'),
            $(that).parents('form').serialize(),
            function(responce){
                if (true === responce.success) {
                    showDiv = $(that).parents('form').data('open-slide');
                    closePageslider(showDiv);
                    $('img').remove();
                    $('.login span>span').html(responce.username);
                    $('.login > span').append("<a href='http://flow.local/user/resetCookie.php'><img style='cursor: pointer;margin-left: 5px;' class='logout' src='images/logout.png' title='Log Out'/></a>");
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
     //          ***    SUBMITTING GENERATION FORM  ***
     $('#generate').on('click', function(e){
         e.preventDefault();
        that = this;
        
        $.get(
            $(that).parents('form').attr('action'),
            $(that).parents('form').serialize(),
            function(responce){
                if(true === responce.success){
                    alert('YEAH!');
                }else{
                    alert('SHIT!!!');
                }
                
            },
            'json'
        );
     });
     
     $('#Ffactor').on('click',function(){
        if(true === $(this).prop("checked")){
            $('ul.input-data').append('<li class="F"><input id="F" type="text" name="F" value="" placeholder="Focus factor"><span>between 0 and 1</span>');
            liveValidation("F").add( Validate.Numericality, {minimum: 0, maximum: 1});
            $('fieldset.generation-method').append('<li class="F"><span>outfocus factor</span>');
        } else{
            $('ul.input-data').find('li.F').remove();
            $('fieldset.generation-method').find('li.F').remove();
        }
     });
     
     $('#T').on('click',function(){
        if(true === $(this).prop("checked")){
            $('fieldset.generation-method').append('<li class="T"><span>triplet states</span>');
        } else{
            $('fieldset.generation-method').find('li.T').remove();
        }
     });
     
     $('#history').on('click',function(){
         if( $('#start').hasClass('active') ){
             $(this).removeClass('passive').addClass('active');
         }
     });
         
     $('#start').on('click',function(){
         if( $('#history').hasClass('active') ){
             $(this).removeClass('active').addClass('passive');
         }
     });
         
     //          ***    VALIDATION GENERATION FORM  ***
    liveValidation("w0").add( Validate.Numericality, {minimum: 0.0000001, maximum: 10});
    liveValidation("z0").add( Validate.Numericality, {minimum: 0.0000001, maximum: 10});
    liveValidation("startTime").add( Validate.Numericality, {minimum: 0, maximum: 3600, onlyInteger: true});
    liveValidation("endTime").add( Validate.Numericality, {minimum: 0, maximum: 3600 });
    liveValidation("diffusion").add( Validate.Numericality, {minimum: 1e-12, maximum: 2.8e-10});
    liveValidation("brightness").add( Validate.Numericality, {minimum: 10000, maximum: 150000, onlyInteger: true});
    liveValidation("Neff").add( Validate.Numericality, {minimum: 0.01, maximum: 5});
     
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
    
    function liveValidation(id){
        var lv = new LiveValidation( 
            id,{
                onValid: function() {
                    this.addFieldClass();
                    if($(this.element).hasClass("LV_valid_field")){
                        setValidColor(this);
                    }
                },
                onInvalid: function() {
                    this.addFieldClass();
                    this.insertMessage(
                        this.createMessageSpan() 
                    );
                    if($(this.element).hasClass("LV_invalid_field")){
                        setInvalidColor(this);
                    }
                },   
                wait: 500
            }
        );
        return lv;
    }
    
    function setValidColor(validator){
        $(validator.element).next('span').removeClass('LV_invalid').addClass('LV_valid');
     }
     
     function setInvalidColor(validator){
        $(validator.element).next('span').next().removeClass('LV_valid').addClass('LV_invalid');
     }
});