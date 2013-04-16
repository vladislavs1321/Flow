<?php

require_once 'Molecules.php';
require_once __DIR__ . '/gauss.php';

/**
 * Description of Flow
 *
 * @author Vladislav
 */
class Flow
{
    public $w0;
    public $z0;
    public $startTime;
    public $endTime;
    public $F = 0;
    public $molecules;
    public $rateAb = 0;
    public $rateBa = 0;
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
    public $fileUploadDir = '/home/vladislav/web/flow.local/data/'; // linux

//    public $fileUploadDir = 'Z:/home/flow.local/www/data/';//windows

    function __construct($w0, $z0, $startTime, $endTime, $diffusion, $Brightness, $Neff, $F , $rateAb, $rateBa)
    {
        $this->w0 = $w0;
        $this->z0 = $z0;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->F = $F;
        $this->rateAb = $rateAb;
        $this->rateBa = $rateBa;

        $this->RXb = 10 * $w0;
        $this->RYb = 10 * $w0;
        $this->RZb = 10 * $z0;
        $this->RV = 8 * 10 * $w0 * 10 * $w0 * 10 * $z0;
        $this->Veff = (1 + $F) * (1 + $F) * pow(pi(), 1.5) * $w0 * $w0 * $z0;

        $this->molecules = new Molecules($diffusion, $Brightness, $Neff, $this->RV, $this->Veff, $this->w0);

        $this->intensity = (1 + $F) * $this->molecules->Brightness;
        $this->invIntensity = 1 / ( (1 + $F) * $this->molecules->Brightness);
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
        $a = -2 / ($w0 * $w0);
        $b = -2 / ($z0 * $z0);
        $c= exp($a * ($X * $X + $Y * $Y) + $b * $Z * $Z);
        return exp($a * ($X * $X + $Y * $Y) + $b * $Z * $Z);
    }

    /**
     * @param type $X
     * @param type $L
     * @return type
     */
    public function periodicBoundTest($X, $L)
    {
        while(abs($X) > $L){
            if (abs($X) > $L) {
                $X = $X - 2 * $L * floor(($X + $L) / (2 * $L));
            }
        }
        if(is_nan($X))
        {
            var_dump($X);
        }
        return $X;
    }

    /**
     * Diffusion
     * @return string
     */
    function simu()
    {
//        $db = new Database();
//        if (false === $db->connect()) {
//            var_dump($db->error);
//            return false;
//        }

        $events = array();
        $numberOfEvents = 0;
//        $flowName = time();
//
//        if (false === $fp = fopen($this->fileUploadDir . $flowName . ".txt", 'w+')) {
//            var_dump("cant create flow_data file in current dir");
//            return false;
//            exit;
//        }
//         
            

        for ($k = 0; $k < $this->molecules->count; $k++) {

            $this->molecules->X = (2 * (float)rand()/(float)getrandmax() - 1) * $this->RXb;
            $this->molecules->Y = (2 * (float)rand()/(float)getrandmax() - 1) * $this->RYb;
            $this->molecules->Z = (2 * (float)rand()/(float)getrandmax() - 1) * $this->RZb;
           
            $xx1= $this->molecules->X;    
            $yy1= $this->molecules->Y;    
            $zz1= $this->molecules->Z;  
            
            $previousEvent = $this->startTime;
            $currentEvent = $previousEvent - $this->invIntensity * log((float)rand()/(float)getrandmax());

            if ($currentEvent < $this->endTime) {

                while (true) {
                    $iterator++;
                    $previousEvent = $currentEvent;
                    $currentEvent = $previousEvent - $this->invIntensity * log((float)rand()/(float)getrandmax());

                    if ($currentEvent > $this->endTime) {
                        break;
                    }
                    $a = (float)rand()/(float)getrandmax() * $this->intensity;
                    $b = (1 + $this->F) * $this->molecules->Brightness * $this->Bfunction(
                                    $this->molecules->X, $this->molecules->Y, $this->molecules->Z, $this->w0, $this->z0);
                    if ($a < $b ) {
                        $numberOfEvents++;
                        $events[$numberOfEvents] = $previousEvent;
                        var_dump($previousEvent);
//                        fwrite($fp, $previousEvent . "\n");
                    }
                    $sigma = sqrt(2 * $this->molecules->diffusion * ($currentEvent - $previousEvent));

                    $this->molecules->X = gauss_ms($this->molecules->X, $sigma);
                    $this->molecules->Y = gauss_ms($this->molecules->Y, $sigma);
                    $this->molecules->Y = gauss_ms($this->molecules->Z, $sigma);

                    $this->molecules->X = $this->periodicBoundTest($this->molecules->X, $this->RXb);
                    if(is_nan($this->molecules->X)){
                        var_dump("pbtX");
                        exit();
                    }
                    $this->molecules->Y = $this->periodicBoundTest($this->molecules->Y, $this->RYb);
                    if(is_nan($this->molecules->y)){
                        var_dump("pbtY");
                        exit();
                    }
                    $this->molecules->Z = $this->periodicBoundTest($this->molecules->Z, $this->RZb);
                    if(is_nan($this->molecules->Z)){
                        var_dump("pbtZ");
                        exit();
                    }
                }
            }
        }

//        fclose($fp);
//        exec("sort -g /home/vladislav/web/flow.local/data/" . $flowName . " -o /home/vladislav/web/flow.local/data/" . $flowName . "");
//        $dataUrl = $this->fileUploadDir . $flowName . ".txt";
//        return $dataUrl;
    }

    //outfocus
    function simu2()
    {
        $db = new Database();
        if (false === $db->connect()) {
            var_dump($db->error);
            return false;
        }
        $flowName = time();
        if (false === $fp = fopen($this->fileUploadDir . $flowName . "temp.txt", 'w+')) {
            var_dump("cant create flow_data file in current dir");
            return false;
            exit;
        }

        $this->outfocusFactor = "outfocus factor";
        $numberOfEvents = 0;
//        $events=array();

        $this->intensity = $this->molecules->Neff * $this->molecules->Brightness * $this->F / ( (1 + $this->F) * sqrt(8) );
        $this->invIntensity = 1 / $this->intensity;

        $previousEvent = $this->startTime;
        $currentEvent = $previousEvent - $this->invIntensity * log((float)rand()/(float)getrandmax());

        if ($currentEvent < $this->endTime) {

            while (true) {
                $previousEvent = $currentEvent;
                $currentEvent = $previousEvent - $this->invIntensity * log((float)rand()/(float)getrandmax());

                if ($currentEvent > $this->endTime) {
                    break;
                }
                $numberOfEvents = $numberOfEvents + 1;
//                    $events[$numberOfEvents]=$previousEvent;

                fwrite($fp, $previousEvent . "\n");
            }
        }
        fclose($fp);
//        exec("sort -g " . $dataUrl . " -o " . $dataUrl . "");
        $dataUrl = $this->fileUploadDir . $flowName . "temp.txt";
        return $dataUrl;
    }

    //Triplets+diffusion
    function simu3()
    {
        $db = new Database();
        if (false === $db->connect()) {
            var_dump($db->error);
            return false;
        }

        $flowName = time();
        if (false === $fp = fopen($this->fileUploadDir . $flowName . ".txt", 'w+')) {
            var_dump("cant create flow_data file in current dir");
            return false;
            exit;
        }

//        $events = array();
        $numberOfEvents = 0;

        $rateAb = $this->rateAb; //in Hz
        $rateBa = $this->rateBa;

        $tA = 1 / $rateAb;
        $tB = 1 / $rateBa;

        $Pa = $rateBa / ($rateAb + $rateBa);
        $Pb = $rateAb / ($rateAb + $rateBa);


        $CurTau = 0;
        $flag = false;

        for ($k = 0; $k < $this->molecules->count; $k++) {

            $this->molecules->X = (2 * (float)rand()/(float)getrandmax() - 1) * $this->RXb;
            $this->molecules->Y = (2 * (float)rand()/(float)getrandmax() - 1) * $this->RYb;
            $this->molecules->Z = (2 * (float)rand()/(float)getrandmax() - 1) * $this->RZb;

            $previousEvent = $this->startTime;
            $currentEvent = $previousEvent - $this->invIntensity * log((float)rand()/(float)getrandmax());

            if ($Pa > (float)rand()/(float)getrandmax()) {
                $state = 'A';
            } else {
                $state = 'B';
            }

            $CurTau = 0;
            ///
            if ($currentEvent < $this->endTime) {
                while (true) {
                    $previousEvent = $currentEvent;
                    $currentEvent = $previousEvent - $this->invIntensity * log((float)rand()/(float)getrandmax());

                    if ($currentEvent > $this->endTime) {
                        break;
                    }

                    $currentIntensity = $this->molecules->Brightness * $this->Bfunction(
                                    $this->molecules->X, $this->molecules->Y, $this->molecules->Z, $this->w0, $this->z0
                    );

                    if ((float)rand()/(float)getrandmax() * $this->intensity < $currentIntensity) {
                        $flag = true;
                        while ($flag) {
                            if ($state == 'A') {
                                if ($previousEvent < $CurTau) {
                                    $numberOfEvents = $numberOfEvents + 1;
                                    fwrite($fp, $previousEvent . "\n");
                                    $flag = false;
                                } else {
                                    $CurTau += -$tB * log((float)rand()/(float)getrandmax());
                                    $state = 'B';
                                }
                            } else {
                                if ($previousEvent > $CurTau) {
                                    $CurTau += -$tA * log((float)rand()/(float)getrandmax());
                                    $state = 'A';
                                } else {
                                    $flag = false;
                                }
                            }
                        }
                    }
                    $sigma = sqrt(2 * $this->molecules->diffusion * ($currentEvent - $previousEvent));

                    $this->molecules->X = gauss_ms($this->molecules->X, $sigma);
                    $this->molecules->Y = gauss_ms($this->molecules->Y, $sigma);
                    $this->molecules->Y = gauss_ms($this->molecules->Z, $sigma);

                    $this->molecules->X = $this->periodicBoundTest($this->molecules->X, $this->RXb);
                    $this->molecules->Y = $this->periodicBoundTest($this->molecules->Y, $this->RYb);
                    $this->molecules->Z = $this->periodicBoundTest($this->molecules->Z, $this->RZb);
                }
            }
        }
        fclose($fp);
        exec("sort -g /home/vladislav/web/flow.local/data/" . $flowName . " -o /home/vladislav/web/flow.local/data/" . $flowName . "");
        $dataUrl = $this->fileUploadDir . $flowName . ".txt";
        return $dataUrl;
    }

    //Triplets + Diffusion + outfocus
    function simu4()
    {
        return $dataUrl = $this->simu2($this->simu3());
    }
    //Diffusion+outfocus
    function simu5()
    {
        $dataUrl = $this->simu();
        $dataUrl2 = $this->simu2();
        exec("sort -g -m " . $dataUrl . " ".$dataUrl2." " .$dataUrl. "");
//        return $dataUrl = $this->simu2($this->simu());
    }
}