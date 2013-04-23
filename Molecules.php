<?php

/**
 * Description of Molecules
 *
 * @author Vladislav
 */
class Molecules {
    public $diffusion   ;
    public $Brightness  ; 
    public $Neff        ;
    
    public $simulMeanCount;
    public $diffusionTime;
    public $count;
    
    public $X; 
    public $Y;
    public $Z;


    function __construct($diffusion, $Brightness, $Neff, $RV ,$Veff, $w0 ) {
        (float)$this->diffusion        = $diffusion;
        (float)$this->Brightness       = $Brightness;
        (float)$this->Neff             = $Neff;
        
        (float)$this->simulMeanCount   = $RV*$diffusion/$Veff; 
        (float)$this->diffusionTime    = $w0*$w0/(4*$diffusion);
        (float)$this->count            = round($RV*$Neff/$Veff);
    }
    
}
?>
