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
    public $Brightness ; 
    public $Neff       ;
    
    public $simulMeanCount;
    public $diffusionTime;
    public $count;
    
    public $X; 
    public $Y;
    public $Z;


    function __construct($diffusion, $Brightness, $Neff, $RV ,$Veff, $w0 ) {
        $this->diffusion        = $diffusion;
        $this->Brightness       = $Brightness;
        $this->Neff             = $Neff;
        
        $this->simulMeanCount   = $RV*$diffusion/$Veff; 
        $this->diffusionTime    = $w0*$w0/(4*$diffusion);
        $this->count            = round($RV*$Neff/$Veff);
    }
    
}
?>
