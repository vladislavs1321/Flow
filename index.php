<?php
   
    session_start();   
    
    if(null !== $_SESSION['auto_error']) {
        $error = $_SESSION['auto_error'];
        var_dump($error);
    } else {
        echo("session in progress");
        var_dump($_SESSION);
    }
?>
<html>
    <head>
        <title></title>
        <link rel='stylesheet' href='style/style.css'/> 
        <link rel='stylesheet' href='style/jquery.pageslide.css'/> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> 
        <script type="text/javascript" src="js/script.js"></script>
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
                <span>
                    <span class="username"></span>
                    <a href="#sing-in-modal-l" class="show-sign-in" title="Log In"> <img src="images/login.png"/></a>
                    <a href="#sing-in-modal-r" class="show-sign-in" title="Sign Up"> <img src="images/sign_up.png"/></a>
                </span>
            </div>
            
            <!-- SI modal login -->
            <div id="sing-in-modal-l" style="display: none;">
                <form id="login-form" method="post" action='user/autorization.php'>
                    <div ><h1>LOG IN</h1></div>
                    <div id="login-box-name" >Username or Email:</div>
                    <div id="login-box-field">
                        <input name="username" class="form-login" title="Username" value="" size="30" maxlength="2048" placeholder="Username" />
                    </div>
                    <div id="login-box-name">Password:</div>
                    <div id="login-box-field">
                        <input name="password" type="password" class="form-login" title="Password" value="" size="30" maxlength="2048" placeholder="**********"/>
                    </div>
                    <div id="login-box-name">
                           <input id="entr" type="submit" name="sbmt" class="form-login" value="Log In"/>
                           <a class="rgstr show-sign-in" href="#sing-in-modal-r" style="text-decoration: underline;">Registration</a>
                    </div>
            </form>
            </div>
            <!-- end SI modal login-->
            
            
             <!-- SI modal registration -->
            <div id="sing-in-modal-r" style="display: none;">
                <form id="login-form" method="post" action='user/User.php'>
                    <div ><h1>REGISTRATION</h1></div>
                    <div id="login-box-name" >Username or Email:</div>
                    <div id="login-box-field">
                        <input name="username" class="form-login" title="Username" value="" size="30" maxlength="2048" placeholder="Username" />
                    </div>
                    <div id="login-box-name">Password:</div>
                    <div id="login-box-field">
                        <input name="password" type="password" class="form-login" title="Password" value="" size="30" maxlength="2048" placeholder="**********"/>
                    </div>
                    <div id="login-box-name">Confirm Password:</div>
                    <div id="login-box-field">
                        <input name="password" type="password" class="form-login" title="Password" value="" size="30" maxlength="2048" placeholder="**********"/>
                    </div>
                    <div id="login-box-name">
                           <input id="entr" type="submit" name="sbmt" class="form-login" value="Sign Up"/>
                    </div>
            </form>
            </div>
            <!-- end SI modal registration -->
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
session_destroy();  
?>