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
    
    if($('#history').length){
        $.getJSON(
                'http://flow.local/user/viewHistory.php',
                function(responce){
                    for(i in responce){
                        var reg = /[0-9]+/i;
                        var time = reg.exec(responce[i].data_url.valueOf());
                        var date = new Date(time[0]*1000);
                        $('.history').find('ul').append('<li class="row">\n\
                            <span class="date-of-creating-flow">'+ date.toUTCString() +'</span>\n\
                            <span class="description-link"><a class="download" href="#" title="view description">view description</a></span>\n\
                            <span class="download"><img src="../images/arrow_down.png"/><span></li>'
                        );
                    }
                },
                'json'
        );
            
        $(function() {
            $('.history').jScrollPane();
        });    
    }
        
    $('body').on('click', 'a.download', function(event){
         event.preventDefault();
         alert("dfg");
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
    
    $('#back').on('click', function() {
        $('.description').hide('clip',2000,function(){
            clearInterval(i);
            $('.varaibles').show('fold',2000);
            
        });
    });
     //          ***    SUBMITTING GENERATION FORM  ***
    $('#generate').on('click', function(e){
//        $('#form1').hide('explode');
        $('body').append('<iframe style="position: absolute;"width="100%" height="100%" src="http://www.neveroccurs.com/lab/three.js/gpu_particles/index.html?particles=256"></iframe>');
        $('#info').empty();
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
            $('ul.input-data').append('<li class="input-data-f"><input id="F" type="text" name="F" value="" placeholder="Focus factor"><span>between 0 and 1</span>');
            $('fieldset.generation-method').append('<li class="generation-method-f"><span>outfocus factor</span>');
            $('fieldset.generation-parametres').append('<li class="generation-parametres-f"><span class="varaible">F factor</span><span class="value F" ></span>');
            if($('#F').length){
                validators.push(liveValidation("F").add( Validate.Numericality, {minimum: 0, maximum: 1}));
            }
        } else{
            $('ul.input-data').find('li.input-data-f').remove();
            $('fieldset.generation-method').find('li.generation-method-f').remove();
            $('fieldset.generation-parametres').find('li.generation-parametres-f').remove();
            validators = validators.slice(0,validators.length-1);
        }
    });
     
    $('#T').on('click',function(){
        if(true === $(this).prop("checked")){
            $('ul.input-data').append('<li class="input-data-tka"><input id="ka" type="text" name="ka" value="" placeholder="Rate Constant A"><span>in Hz</span>');
            $('ul.input-data').append('<li class="input-data-tkb"><input id="kb" type="text" name="kb" value="" placeholder="Rate Constant B"><span>in Hz</span>');
            if($('#ka').length){
                validators.push(liveValidation("ka").add( Validate.Numericality, {minimum: 500000, maximum: 1500000}));
            }if($('#kb').length){
                validators.push(liveValidation("kb").add( Validate.Numericality, {minimum: 500000, maximum: 1500000}));
            }
            $('fieldset.generation-method').append('<li class="T"><span>triplet states</span>');
        } else{
            $('fieldset.generation-method').find('li.T').remove();
            $('ul.input-data').find('li.input-data-tka').remove();
            $('ul.input-data').find('li.input-data-tkb').remove();
        }
    });
     
    $('#history').on('click',function(){
        if( $('#start').hasClass('active') ){
            $('#start').removeClass('active').addClass('passive');
            $('#history').addClass('active');
        }
    });
         
    $('#start').on('click',function(){
        if( $('#history').hasClass('active') ){
            $('#history').removeClass('active').addClass('passive');
            $('#start').addClass('active');
        }
    });
         
     //          ***    VALIDATION GENERATION FORM  ***
    var validators = [];
    if($('#w0').length){validators.push(liveValidation("w0").add( Validate.Numericality, {minimum: 0.0000001, maximum: 10}));}
    if($('#z0').length){validators.push(liveValidation("z0").add( Validate.Numericality, {minimum: 0.0000001, maximum: 10}));}
    if($('#startTime').length){validators.push(liveValidation("startTime").add( Validate.Numericality, {minimum: 0, maximum: 3600, onlyInteger: true}));}
    if($('#endTime').length){validators.push(liveValidation("endTime").add( Validate.Numericality, {minimum: 0, maximum: 3600 }));}
    if($('#diffusion').length){validators.push(liveValidation("diffusion").add( Validate.Numericality, {minimum: 1e-12, maximum: 2.8e-10}));}
    if($('#Brightness').length){validators.push(liveValidation("Brightness").add( Validate.Numericality, {minimum: 10000, maximum: 150000, onlyInteger: true}));}
    if($('#Neff').length){validators.push(liveValidation("Neff").add( Validate.Numericality, {minimum: 0.01, maximum: 5}));}
    
   var i = setInterval(function() {
        var areAllValid = LiveValidation.massValidate(validators);
        if( true === areAllValid ) {
            if($('input.LV_valid_field').length === validators.length){
                window.clearInterval(i);
                $('#generate').css({"display":"inline"}).removeAttr('disabled');
                $('#form1').effect('pulsate', 1000).effect('shake', 400).effect('bounce',200);
            }
        }
    }, 200);
   
    
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
                    $('.'+ id ).empty().append($(this.element).val());
                },
                onInvalid: function() {
                    this.addFieldClass();
                    this.insertMessage(
                        this.createMessageSpan() 
                    );
                    if($(this.element).hasClass("LV_invalid_field")){
                        setInvalidColor(this);
                    }
                    $('span.value.' + id).empty();
                }
                ,   
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