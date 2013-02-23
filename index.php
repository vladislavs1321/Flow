<html>
    <head>
        <title></title>
        <link rel='stylesheet' href='style/style.css'/> 
        <link rel='stylesheet' href='style/jquery.pageslide.css'/> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    </head>

    <body>
        <div class="header">
            Flow Generation
        </div>

        <div class="content">

            <div class="main-menu">
                <div class="option-button">
                    START
                </div>

                <div class="option-button">
                    DISCRIPTION
                </div>

                <div class="option-button">  
                    DOWNLOAD
                </div>
            </div>
            <!--            
                        <div class="vars" >
                            <input type="text" name="w0" value="" placeholder="w0">
                            <input type="text" name="z0" value="" placeholder="z0">
                            <input type="text" name="startTime" value="" placeholder="Start time">
                            <input type="text" name="endTime" value="" placeholder="End time">
                            <input type="text" name="F" value="" placeholder="F">
                            <input type="text" name="diffusion" value="" placeholder="diffusion">
                            <input type="text" name="brightness" value="" placeholder="brightness">
                            <input type="text" name="Neff" value="" placeholder="Neff">
                            </div>
                        
                    
                    </div>-->
            
            <div class="login">
                <a href="#sing-in-modal" class="show-sign-in" title="Log In"> <img src="images/login.png"/></a>
                <a href="#" title="Sign Up"> <img src="images/sign_up.png"/></a>
            </div>
            
            <!-- SI modal -->
            <div id="sing-in-modal" style="display: none;">
                <form method="post">
                    <div ><h1>LOG IN</h1></div>
                    <div id="login-box-name" >Username or Email:</div>
                    <div id="login-box-field">
                        <input name="q" class="form-login" title="Username" value="" size="30" maxlength="2048" placeholder="Username" />
                    </div>

                    <div id="login-box-name">Password:</div>
                    <div id="login-box-field"><input name="q" type="password" class="form-login" title="Password" value="" size="30" maxlength="2048" placeholder="**********"/>
                    </div>
                    <div id="login-box-name">
                        <a class="btn" href="javascript:$.pageslide.close()">Enter</a>
                    </div>
                </form>
            </div>
            <!-- end SI modal -->
            
        </div>
        
        
        
        <div class="footer">
        </div>
        
        <script type="text/javascript" src="js/jquery.pageslide.min.js"></script>
        <script>$(".show-sign-in").pageslide({ direction: "left"});</script>
    </body>
</html>


<?php
require_once 'Database.php';
require_once 'Flow.php';


//$f = new Flow(0.3e-6, 0.9e-6, 0, 0.1, 0.4,  0.0000000028, 100000, 0.01);
//$f->simu();
//var_dump($f);
//var_dump($f->simu2());
//var_dump(rand(1,10000)*0.0001);
?>
