<html>
    <head>
        <title></title>
        <link rel='stylesheet' href='style/style.css'/> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    
    <body>
        <div class="bg-image">
            
            <h1><?php echo 'HELLO'?></h1>
        
        </div>
    </body>
</html>


<?php

require_once 'Database.php';
require_once 'Flow.php';


$f = new Flow(0.3e-6, 0.9e-6, 0, 0.1, 0.4,  0.0000000028, 100000, 0.01);
$f->simu();
var_dump($f);
//var_dump($f->simu2());
//var_dump(rand(1,10000)*0.0001);


?>
