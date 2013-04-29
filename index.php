<?php
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);
require_once './user/checkCookie.php';
require_once './Flow.php';
require_once './user/User.php';
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

            <div class="block-2"></div>
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