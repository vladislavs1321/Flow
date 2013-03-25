<?php

require_once 'Molecules.php';
require_once 'gauss.php';

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
    
    
    public $fileUploadDir = 'S:\web\flow.local\data';
   
    function __construct($w0, $z0, $startTime, $endTime, $F, $diffusion, $brightness, $Neff) {
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
        
        $this->molecules = new Molecules($diffusion, $brightness, $Neff, $this->RV ,$this->Veff, $this->w0);
        
        $this->intensity    = (1+$F)*$brightness;
        $this->invIntensity = 1/( (1+$F)*$brightness);
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
        $fp = fopen($flowName."txt", 'w+');
        
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
                    
                    if(rand(1,10000)*0.0001*$this->intensity < (1+$this->F)*$this->molecules->brightness
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
        
        var_dump($events);
        fclose($fp);
        $query = sprintf("INSERT INTO flow.flow_data (data, user_id  ) VALUES ('%s', %d)",
            addslashes(__DIR__).addslashes('\f1.txt'),
            1
        );
        if(false === $db->unselect($query)){
            var_dump($db->error);
        };
        var_dump($query);
    }
    
    function simu2(){
        ini_set('memory_limit', '128M');
        $numberOfEvents=0;
        $events=array();
        $fp = fopen('data/f1.txt', 'w+');
        
        $this->intensity = $this->molecules->Neff*$this->molecules->brightness*$this->F/( (1+$this->F)*sqrt(8) );
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