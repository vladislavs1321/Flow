<?php

require_once 'Molecules.php';

class Flow
{

    public $w0;
    public $z0;
    public $startTime;
    public $endTime;
    public $F;
    public $molecules;
    public $rateAb;
    public $rateBa;
    public $RXb;
    public $RYb;
    public $RZb;
    public $RV;
    public $Veff;
    public $intensity;
    public $invIntensity;
//    public $fileUploadDir = '/home/vladislav/web/flow.local/data/'; // linux
//    public $fileUploadDir = '/home/varloc2000/web/flow.local/data/'; // linux

    public $fileUploadDir = 'Z:/home/flow.local/www/data/';//windows

    function __construct($w0, $z0, $startTime, $endTime, $diffusion, $Brightness, $Neff, $F, $rateAb, $rateBa)
    {
        (float) $this->w0 = $w0;
        (float) $this->z0 = $z0;
        (float) $this->startTime = $startTime;
        (float) $this->endTime = $endTime;
        (float) $this->F = $F;
        (float) $this->rateAb = $rateAb;
        (float) $this->rateBa = $rateBa;

        (float) $this->RXb = 10 * $w0;
        (float) $this->RYb = 10 * $w0;
        (float) $this->RZb = 10 * $z0;
        (float) $this->RV = 8 * $this->RXb * $this->RYb * $this->RZb;
        (float) $this->Veff = (1 + $F) * (1 + $F) * pow(pi(), 1.5) * $w0 * $w0 * $z0;

        $this->molecules = new Molecules($diffusion, $Brightness, $Neff, $this->RV, $this->Veff, $this->w0);

        (float) $this->intensity = (1 + $F) * $this->molecules->Brightness;
        (float) $this->invIntensity = 1 / $this->intensity;
    }

    function s()
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

        function normrnd($dM, $dD)
        {

// In the result of work of this generator one recives
// two gauss random value. One of them is used as a result (dNVal2)
// and one stays for the next call of function due too C++
// mechanism of static variables

            (bool) $bFlag_Val2 = false;
            (double) $dNVal2;
            (double) $dFac;
            (double) $dR;

            if ($bFlag_Val2) { // We have an extra deviate handy, so
                (bool) $bFlag_Val2 = false; // so unset the flag,
                return $dNVal2 * $dD + $dM; // and return it.
            }
            do {
                (double) $dUVal1 = 2.0 * (double) rand() / (double) getrandmax() - 1.0; // pick two uniform numbers in the square extending from -1 to +1 in each direction
                (double) $dUVal2 = 2.0 * (double) rand() / (double) getrandmax() - 1.0;

                $dR = $dUVal1 * $dUVal1 + $dUVal2 * $dUVal2; // see if they are in the unit circle,
            } while ($dR >= 1.0 || $dR == 0.0); // and if they are not, try again.

            $dFac = sqrt(-2.0 * log($dR) / $dR);
// Now make the Box-Muller transformation to get two normal deviates.
// Return one and save the other for next time.
            $dNVal2 = $dUVal1 * $dFac;
            (bool) $bFlag_Val2 = true; // Set flag.
            return $dUVal2 * $dFac * $dD + $dM;
        }

        function B_function($x, $y, $z, $w0_0, $z0_0)
        {

            (float) $a = -2.0 / ($w0_0 * $w0_0);
            (float) $b = -2.0 / ($z0_0 * $z0_0);
            return exp($a * ($x * $x + $y * $y) + $b * $z * $z);
        }

        function PeriodicBoundTest($X, $L)
        {
            if (abs($X) > $L) {
                $X = $X - 2 * $L * floor(($X + $L) / (2 * $L));
            }
            return $X;
        }

        (float) $w0 = $this->w0;    // in meters
        (float) $z0 = $this->z0;    // in meters
        (float) $F = $this->F;

        //% Modelling area
        (float) $R_Xb = 10 * $w0;
        (float) $R_Yb = 10 * $w0;
        (float) $R_Zb = 10 * $z0;

        // Volumes
        (float) $R_V = 8 * $R_Xb * $R_Yb * $R_Zb;

        //% Standard volume of FCS
        (float) $Veff = (1 + $F) * (1 + $F) * ( pow(pi(), 1.5) ) * $w0 * $w0 * $z0;

        //% Molecules

        (float) $Molecules_Diffusion = $this->molecules->diffusion;
        (float) $Molecules_Brightness = $this->molecules->Brightness; // in Hz
        (float) $Molecules_Neff = $this->molecules->Neff;

        (float) $Molecules_SimulMeanCount = $R_V * $Molecules_Neff / $Veff;
        (float) $Molecules_DiffusionTime = $w0 * $w0 / (4 * $Molecules_Diffusion);
        (float) $Molecules_Count = round($Molecules_SimulMeanCount);
        //Triplet states
        (float) $Kab = $this->rateAb; //in Hz
        (float) $Kba = $this->rateBa;

        (float) $Ta = 1 / $Kab;
        (float) $Tb = 1 / $Kba;

        (float) $Pa = $Kba / ($Kab + $Kba);
        (float) $Pb = $Kab / ($Kab + $Kba);

        (float) $CurTau = 0.0;
        (bool) $flag = false;

        (int) $NumberOfEvents = 0;
        (int) $FNumberOfEvents = 0;

        (float) $Intensity = (1 + $F) * $Molecules_Brightness;

        (float) $InvI = 1.0 / $Intensity;
        (float) $PreviousEvent = 0;  //% For Brownian motion 
        (float) $CurrentEvent = 0;   //% For Brownian motion

        (float) $CurrIntensity = 0.0;
        (float) $StartTime = $this->startTime;
        (float) $EndTime = $this->endTime;

        //(float) $BB = B_function(0.0, 0.0, 0.0, $w0, $z0);
        for ($k = 0; $k < $Molecules_Count; $k++) {
            var_dump($k);
            (float) $Molecules_X = (2.0 * (float) rand() / (float) getrandmax() - 1.0) * $R_Xb;
            (float) $Molecules_Y = (2.0 * (float) rand() / (float) getrandmax() - 1.0) * $R_Yb;
            (float) $Molecules_Z = (2.0 * (float) rand() / (float) getrandmax() - 1.0) * $R_Zb;

            $PreviousEvent = $StartTime;  //  % Start generation from this moment of time
            $CurrentEvent = $PreviousEvent - $InvI * log((float) rand() / (float) getrandmax());  //% The first event of the flow
            // State
            if ($Pa > (float) rand() / (float) getrandmax()) {
                $State = 'A';
            } else {
                $State = 'B';
            }

            (float) $CurTau = 0.0;
            if ($CurrentEvent < $EndTime) {

                while (true) {
                    $PreviousEvent = $CurrentEvent;
                    $CurrentEvent = $PreviousEvent - $InvI * log((float) rand() / (float) getrandmax());

                    if ($CurrentEvent > $EndTime) {
                        break;
                    }
                    $BB = B_function($Molecules_X, $Molecules_Y, $Molecules_Z, $w0, $z0);
                    $CurrIntensity = (1 + $F) * $Molecules_Brightness * $BB;
//  % Decimation of the flow

                    if ((float) rand() / (float) getrandmax() * $Intensity < $CurrIntensity) {
                        if ($Kab > 0 && $Kba > 0) {
                            (bool) $flag = true;
                            while ($flag) {
                                if ($State == 'A') {
                                    if ($PreviousEvent < $CurTau) {
                                        $NumberOfEvents = $NumberOfEvents + 1;
                                        var_dump($PreviousEvent);
                                        fwrite($fp, $PreviousEvent . "\n");
                                        $flag = false;
                                    } else {
                                        $CurTau += -$Tb * log((float) rand() / (float) getrandmax());
                                        $State = 'B';
                                    }
                                } else {
                                    if ($PreviousEvent > $CurTau) {
                                        $CurTau += -$Ta * log((float) rand() / (float) getrandmax());
                                        $State = 'A';
                                    } else {
                                        $flag = false;
                                    }
                                }
                            }
                        } else {
                            $NumberOfEvents = $NumberOfEvents + 1;
                            $Events[$NumberOfEvents] = $PreviousEvent;
                            var_dump($PreviousEvent);
                            fwrite($fp, $PreviousEvent . "\n");
                        }
                    }
// % Brownian Movement of a molecule

                    (float) $Sigma = sqrt(2 * $Molecules_Diffusion * ($CurrentEvent - $PreviousEvent));

                    $Molecules_X = normrnd($Molecules_X, $Sigma);
                    $Molecules_Y = normrnd($Molecules_Y, $Sigma);
                    $Molecules_Z = normrnd($Molecules_Z, $Sigma);


// %  Periodic boundary conditions    
                    $Molecules_X = PeriodicBoundTest($Molecules_X, $R_Xb);
                    $Molecules_Y = PeriodicBoundTest($Molecules_Y, $R_Yb);
                    $Molecules_Z = PeriodicBoundTest($Molecules_Z, $R_Zb);
                }
            }
        }

        if ($F > 0) {
            $Intensity = $Molecules_Neff * $Molecules_Brightness * $F / ((1 + $F) * sqrt(8));
            $InvI = 1 / $Intensity;

            $PreviousEvent = $StartTime;
            $CurrentEvent = $PreviousEvent - $InvI * log((float) rand() / (float) getrandmax());

            if ($CurrentEvent < $EndTime)
                while (true) {

                    $PreviousEvent = $CurrentEvent;
                    $CurrentEvent = $PreviousEvent - $InvI * log((float) rand() / (float) getrandmax());

                    if ($CurrentEvent > $EndTime) {
                        break;
                    }
                    $NumberOfEvents = $NumberOfEvents + 1;
                    $Events[$NumberOfEvents] = $PreviousEvent;
                    $FNumberOfEvents = $FNumberOfEvents + 1;
                    var_dump($PreviousEvent."  F factor");
                    fwrite($fp, $PreviousEvent . "\n");
                }
        }
        fclose($fp);
        exec("sort -g /home/vladislav/web/flow.local/data/" . $flowName . ".txt -o /home/vladislav/web/flow.local/data/" . $flowName . ".txt");
        exec("find /home/vladislav/web/flow.local/data -name ".$flowName.".txt -exec zip '{}.zip' '{}' \;");
//        exec("find . -name ".$flowName.".txt -exec zip '{}.zip' '{}' \;");
        $dataUrl = $this->fileUploadDir . $flowName . ".txt";
        return $dataUrl;
//        var_dump($Events);
    }

}
?>