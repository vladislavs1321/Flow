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
                alert(responce);
            }
        )
       
    });
    
});
