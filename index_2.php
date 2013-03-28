<?php
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);
require_once './user/checkCookie.php';
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
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery-1.9.min.js"></script>
        <script type="text/javascript" src="js/jquery.animateshadow.min.js"></script>
        <script type="text/javascript" src="js/jquery.cookies.2.2.0.min.js"></script>
    </head>

    <body>
        <div class="matherfacker">
            <div class="header">
                <span>Flow Generation</span>
            </div>
            
            <div class="new-content">
                <div class="block-1"></div>
                
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
        </div>
        
        <!--         Hidden element to initialize pageslider open/close -->
        <a href="#modal" id="pageslider-initiator" style="display: none;"></a>
        
        <script type="text/javascript" src="js/jquery.pageslide.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
    </body>
</html>


<?php
require_once './Flow.php';
require_once './user/User.php';

//var_dump(rand(1,10000)*0.0001);
?>