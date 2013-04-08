<?php

/**
 * Description of Molecules
 *
 * @author Vladislav
 */
class Molecules {
    public $diffusion   = 0.0000000028;
    public $brightness  = 100000; 
    public $Neff        = 0.01;
    
    public $simulMeanCount;
    public $diffusionTime;
    public $count;
    
    public $X; 
    public $Y;
    public $Z;


    function __construct($diffusion, $brightness, $Neff, $RV ,$Veff, $w0 ) {
        $this->diffusion        = $diffusion;
        $this->brightness       = $brightness;
        $this->Neff             = $Neff;
        
        $this->simulMeanCount   = $RV*$diffusion/$Veff; 
        $this->diffusionTime    = $w0*$w0/(4*$diffusion);
        $this->count            = round($RV*$Neff/$Veff);
    }
    
}
?>
