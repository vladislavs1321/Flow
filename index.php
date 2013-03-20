<?php
?>
<html>
    <head>
        <title></title>
        <link rel='stylesheet' href='style/style.css'/> 
        <link rel='stylesheet' href='style/jquery.pageslide.css'/> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        <!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>-->
        <script type="text/javascript" src="js/jquery-1.9.min.js"></script>
        <script type="text/javascript" src="js/jquery.animateshadow.min.js"></script>
    </head>

    <body>
        <div class="header">
            Flow Generation
        </div>

        <div class="content">
            <div class="x">
                
                <div class="main-menu">
                    <div class="option-button">START</div>
                    <div class="option-button">DESCRIPTION</div>
                    <div class="option-button">DOWNLOAD</div>
                </div>
                <div class="vars">
                    <form class="varaibles" method="get" action="#" >
                        <ul>
                            <li><input type="text" name="w0" value="" placeholder="w0"><span>in metres</span>
                            <li><input type="text" name="z0" value="" placeholder="z0"><span>in metres</span>
                            <li><input type="text" name="startTime" value="" placeholder="Start time"><span>in seconds</span>
                            <li><input type="text" name="endTime" value="" placeholder="End time"><span>in seconds</span>
                            <li><input type="text" name="F" value="" placeholder="Focus factor"><span>q</span>
                            <li><input type="text" name="diffusion" value="" placeholder=" Molecules diffusion"><span>q</span>
                            <li><input type="text" name="brightness" value="" placeholder="brightness"><span>in Hz</span>
                            <li><input type="text" name="Neff" value="" placeholder="Neff"><span>q</span>
                        </ul>
                        <input type="submit" value="generate">
                    </form>
                </div>
            </div>

            


            
            
            <div id="modal">
                <!-- SI modal login -->
                <div data-slider="login" class="sub-modal" style="display: none;">
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
                               <a class="rgstr show-pageslider" data-open-slide="register" href="#" style="text-decoration: underline;">Registration</a>
                        </div>
                        <div id="login-box-name" class="error-message"></div>
                    </form>
                </div>
                <!-- end SI modal login-->
                
                <!-- SI modal registration -->
                <div data-slider="register" class="sub-modal" style="display: none;">
                    <form id="login-form" method="post" action='user/registration.php'>
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
                            <input name="confirmPassword" type="password" class="form-login" title="Password" value="" size="30" maxlength="2048" placeholder="**********"/>
                        </div>
                        <div id="login-box-name">
                               <input id="entr" type="submit" name="sbmt" class="form-login sign-up" value="Sign Up"/>
                        </div>
                        <div id="login-box-name" class="error-message"></div>
                    </form>
                </div>
                <!-- end SI modal registration -->
            </div>
            
        </div>
        
        <div class="login">
            <span>
                <span class="username"></span>
                <img class="show-pageslider" data-open-slide="login" src="images/login.png" title="Log In"/>
                <img class="show-pageslider" data-open-slide="register" src="images/sign_up.png" title="Sign Up"/>
            </span>
        </div>
        
        <div class="footer">
        </div>
        
        <!-- Hidden element to initialize pageslider open/close -->
        <a href="#modal" id="pageslider-initiator" style="display: none;"></a>
        
        <script type="text/javascript" src="js/jquery.pageslide.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
    </body>
</html>


<?php

//$f = new Flow(0.3e-6, 0.9e-6, 0, 0.1, 0.4,  0.0000000028, 100000, 0.01);
//$f->simu();
//var_dump($f);
//var_dump($f->simu2());
//var_dump(rand(1,10000)*0.0001);
?>