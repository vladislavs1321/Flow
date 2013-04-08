<?php

require_once 'Molecules.php';
require_once __DIR__.'/gauss.php';

/**
 * Description of Flow
 *
 * @author Vladislav
 */
class Flow {
    
    public $w0;
    public $z0;
    public $startTime;
    public $endTime;
    public $F;
    public $molecules;
    
    public $RXb;
    public $RYb;
    public $RZb;
    public $RV;
    public $Veff;
    public $intensity;
    public $invIntensity;
    
    public $moleculesDiffusion = "molecules diffusion";
    public $outfocusFactor = "";
    public $tripletStates = "triplet states";
    
//    public $fileUploadDir = '/home/vladislav/web/flow.local/data/';// linux
    public $fileUploadDir = 'Z:/home/flow.local/www/data/';//windows
   
    function __construct($w0, $z0, $startTime, $endTime, $diffusion, $Brightness, $Neff, $F) {
        $this->w0           = $w0;
        $this->z0           = $z0;
        $this->startTime    = $startTime;
        $this->endTime      = $endTime;
        $this->F            = $F;
        
        $this->RXb          = 10*$w0;
        $this->RYb          = 10*$w0;
        $this->RZb          = 10*$z0;
        $this->RV           = 8*10*$w0*10*$w0*10*$z0;
        $this->Veff         = (1+$F)*(1+$F)*pow(pi(),1.5)*$w0*$w0*$z0;
        
        $this->molecules = new Molecules($diffusion, $Brightness, $Neff, $this->RV ,$this->Veff, $this->w0);
        
        $this->intensity    = (1+$F)*$Brightness;
        $this->invIntensity = 1/( (1+$F)*$Brightness);
    }
    
    /**
     * @param type $X
     * @param type $Y
     * @param type $Z
     * @param type $w0
     * @param type $z0
     * @return type
     */
    public function Bfunction($X, $Y, $Z, $w0, $z0)
    {
        $a = -2/($w0*$w0);
        $b = -2/($z0*$z0);
        return exp( $a*($X*$X + $Y*$Y) + $b*$Z*$Z );
    }

    /**
     * @param type $X
     * @param type $L
     * @return type
     */
    public function periodicBoundTest($X, $L)
    {
        if( abs($X) > $L ){
            $X = $X - 2*$L*floor( ($X + $L)/(2*$L) );
        }
        return $X;
    }
    
    /**
     * @return string
     */
    
        function simu() {
        $db = new Database();
        if (false === $db->connect()){
            var_dump($db->error);
            return fasle;    
        }
        
        $events = array();
        $numberOfEvents = 0;
        $flowName = time();
        
        if(false === $fp = fopen($this->fileUploadDir . $flowName.".txt", 'w+')){
            var_dump("cant create flow_data file in current dir");
            return false;
            exit;
        }
        $json_descr="{'w0':'','z0':'','startTime':'','endTime':'','diffusion':'','intencity':'','Neff':'','F':'',}";
        $description =   "######### DESCRIPTION #########\n\n
                                >>> Flow Generation Method <<<\n
                                ----" . $this->moleculesDiffusion ."\n
                                ----". $this->outfocusFactor ."\n
                                ----". $this->tripletStates ."\n\n
                                >>> Parametrs of Generation <<<\n\n
                                ----w0---------------".$this->w0."\n
                                ----z0---------------".$this->z0."\n
                                ----startTime--------".$this->startTime."\n
                                ----endTime----------".$this->endTime."\n
                                ----Intensity--------".$this->diffusion."\n
                                ----Brightness-------".$this->molecules->Brightness."\n    
                                ----Neff-------------".$this->molecules->Neff."\n
                                ----F----------------".$this->F."\n";
        
        $descriptionName= $flowName."description";
        if(false === $dfp = fopen($this->fileUploadDir . $descriptionName.".txt", 'w+')){
            var_dump("cant create flow_data file in current dir");
            return false;
            exit;
        }
        fwrite($dfp, $description);
        fclose($dfp);
        for ($k = 0; $k < $this->molecules->count; $k++){
            
            $this->molecules->X = (2*rand(1,10000)*0.0001-1)*$this->RXb;
            $this->molecules->Y = (2*rand(1,10000)*0.0001-1)*$this->RYb;
            $this->molecules->Z = (2*rand(1,10000)*0.0001-1)*$this->RZb;
            
            $previousEvent=$this->startTime;
            $currentEvent=$previousEvent-$this->invIntensity*log(rand(1,10000)*0.0001);
            
            if( $currentEvent < $this->endTime){
                
                while(true){
                    
                    $previousEvent = $currentEvent;
                    $currentEvent = $previousEvent-$this->invIntensity*log(rand(1,10000)*0.0001);
                    
                    if($currentEvent > $this->endTime){
                        break;
                    }
                    
                    if(rand(1,10000)*0.0001*$this->intensity < (1+$this->F)*$this->molecules->Brightness
                          *$this->Bfunction(
                                    $this->molecules->X,
                                    $this->molecules->Y,
                                    $this->molecules->Z,
                                    $this->w0,
                                    $this->z0
                                  )
                            ){
                                $numberOfEvents++;
                                $events[$numberOfEvents] = $previousEvent;
                                fwrite($fp, $previousEvent."\n");
                    }
                    $sigma = sqrt(2*$this->molecules->diffusion*($currentEvent-$previousEvent));
                    
                    $this->molecules->X = gauss_ms($this->molecules->X, $sigma);
                    $this->molecules->Y = gauss_ms($this->molecules->Y, $sigma);
                    $this->molecules->Y = gauss_ms($this->molecules->Z, $sigma);
                    
                    $this->molecules->X = $this->periodicBoundTest($this->molecules->X,  $this->RXb);
                    $this->molecules->Y = $this->periodicBoundTest($this->molecules->Y,  $this->RYb);
                    $this->molecules->Z = $this->periodicBoundTest($this->molecules->Z,  $this->RZb);
                }
            }
        }
        
        fclose($fp);
        
        $dataUrl = $this->fileUploadDir . $flowName.".txt";
        return $dataUrl;
    }
    
    function simu2(){
        $this->outfocusFactor = "outfocus factor";
        ini_set('memory_limit', '128M');
        $numberOfEvents=0;
        $events=array();
        $fp = fopen('data/f1.txt', 'w+');
        
        $this->intensity = $this->molecules->Neff*$this->molecules->Brightness*$this->F/( (1+$this->F)*sqrt(8) );
        $this->invIntensity=1/$this->intensity;
        
        $previousEvent = $this->startTime;
        $currentEvent=$previousEvent - $this->invIntensity*log(rand(1,10000)*0.0001);
        
            if( $currentEvent< $this->endTime){
                
                while(true){
                    $previousEvent=$currentEvent;
                    $currentEvent=$previousEvent - $this->invIntensity*log(rand(1,10000)*0.0001);

                    if( $currentEvent > $this->endTime){
                        break;
                    }
                    $numberOfEvents=$numberOfEvents+1;
                    $events[$numberOfEvents]=$previousEvent;
             
                    fwrite($fp, $previousEvent."\n");
                }
            }
            var_dump($events);
      fclose($fp);
    }
}