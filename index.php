<?php
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);
require_once './user/checkCookie.php';
require_once './Flow.php';
require_once './user/User.php';
//$f = new Flow(3e-7, 9e-7, 0, 1, 2.8e-10, 100000, 0.01, 0.4, 1100000, 490000);
//$f = new Flow(3e-7, 9e-7, 0, 1, 2.8e-10, 100000, 0.01, 0.4, 0, 0);
//var_dump($f);
//$f->s();
$user = checkCookie();
if (is_object($user)){
    $username = $user->getUsername();
}
 
?>
<html>
    <head>
        <title></title>
        <link rel='stylesheet' href='style/style.css'/> 
        <link rel='stylesheet' href='style/jquery.pageslide.css'/> 
        <link rel='stylesheet' href='style/jquery.mCustomScrollbar.css'/> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        <script type="text/javascript" src="js/jquery-1.9.min.js"></script>
        <script type="text/javascript" src="js/jquery.animateshadow.min.js"></script>
        <script type="text/javascript" src="js/jquery.cookies.2.2.0.min.js"></script>
        <script type="text/javascript" src="js/livevalidation-1.3.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.10.2.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.jscrollpane.min.js"></script>
        <script type="text/javascript" src="js/jquery.mCustomScrollbar.min.js"></script>
        
    </head>

    <body>
            <div class="header">
                <span>Flow Generation</span>
            </div>
            
            <div class="new-content">
                
                <div class="block-1">
                    <div class="main-menu">
                        <div id="start" class="option-button passive">START</div>
                        <div id ="history" class="option-button active">HISTORY</div>
                    </div>
                </div>
                
                <div class="block-2">
<!--                    <div class="vars" hidden="false">
                        <form id="form1" class="varaibles" method="get" action="user/generateFlow.php" >
                            <ul class="input-data">
                                <div>
                                    <div class="varaibles-header">
                                        <fieldset>
                                            <legend><span>&nbsp;Generation Method&nbsp;</span></legend>
                                            <ul class="chkbx">
                                                <li>
                                                    <input id="Ffactor" type="checkbox" name="f1" value="f1"><span>Focus factor</span>
                                                    <input id="T" type="checkbox" name="f2" value="f2"><span>Triplet</span>
                                                </li>
                                            </ul>
                                        </fieldset>
                                    </div>
                                </div>
                                <fieldset>
                                    <legend><span class="varaibles-header">&nbsp;Input Data&nbsp;</span></legend>
                                </fieldset>
                                <li><input id="w0"type="text" name="w0" value="" placeholder="w0"><span>in metres</span>
                                <li><input id="z0" type="text" name="z0" value="" placeholder="z0"><span>in metres</span>
                                <li><input id="startTime" type="text" name="startTime" value="" placeholder="Start time"><span>in seconds</span>
                                <li><input id="endTime" type="text" name="endTime" value="" placeholder="End time"><span>in seconds</span>
                                <li><input id="diffusion" type="text" name="diffusion" value="" placeholder="Molecules diffusion"><span>metres<sup>2</sup>/seconds</span>
                                <li><input id="Brightness" type="text" name="Brightness" value="" placeholder="Brightness"><span>in Hz</span>
                                <li><input id="Neff" type="text" name="Neff" value="" placeholder="Molecules in a volume"><span>pcs</span>
                            </ul>
                            <input id="generate" type="button" form="form1" class="btn" value="<<GENERATE>>" disabled="true" style="display: none">
                        </form>
                    </div>    -->
<!--                <div id="history" class="history">
                        <ul></ul>
                    </div>-->
                       
                </div>
                <div class="block-3">
                    <div class="login">
                        <span>
                            <span class="username"><?php echo(isset($username) ? $username : ""); ?></span>
                            <?php if(!isset($username)):?>
                            <img class="show-pageslider" data-open-slide="login" src="images/login.png" title="Log In"/>
                            <img class="show-pageslider" data-open-slide="register" src="images/sign_up.png" title="Sign Up"/>
                            <?php else:?>
                            <a href='http://flow.local/user/resetCookie.php'><img style='cursor: pointer;margin-left: 5px;' class='logout' src='images/logout.png' title='Log Out'/></a>
                            <?php endif;?>
                        </span>
                    </div>
                </div>
            </div>
        <div id="modal">
<!--                 SI modal login -->
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
<!--                 end SI modal login-->
                
<!--                 SI modal registration -->
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
        
        <!--         Hidden element to initialize pageslider open/close -->
        <a href="#modal" id="pageslider-initiator" style="display: none;"></a>
        
        <script type="text/javascript" src="js/jquery.mousewheel.min.js"></script>
        <script type="text/javascript" src="js/jquery.mCustomScrollbar.min.js"></script>
        <script type="text/javascript" src="js/jquery.pageslide.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
    </body>
</html>


<?php

//$f->s();
//    for($i;$i<10;$i++){
//$f->simu();
//}
?>