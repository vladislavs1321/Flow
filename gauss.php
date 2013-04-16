<?php
function gauss_ms($m = 0.0, $s = 1.0) { 
    return gauss() * $s + $m;
}

function gauss() { 
    $x = random_0_1();
    $y = random_0_1();

    $u = sqrt(-2 * log($x)) * cos(2 * pi() * $y);
    //$v=sqrt(-2*log($x))*sin(2*pi()*$y);
    return $u;
}

function random_0_1() { 
    return (float) rand() / (float) getrandmax();
}

?>