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
                    switch (responce.error.code){
                        //no username and password
                        case 1:{
                                $('#login-box-field > input').css("outline", "2px solid red");
                        }
                        
//                        case 2:
//                        case 3:
//                        case 4:
//                        case 5:
//                        case 6:
//                        case 7:
//                        case 8:
//                        case 9:
//                        case 10:
//                        case 11:
                            
                        
                    }
                }
                

            },
            'json'
        );
       
    });
    
});
