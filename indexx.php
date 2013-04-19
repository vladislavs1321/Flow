<?php
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);
//require_once './gauss.php';
function normrnd($dM, $dD) {

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

function B_function($x, $y, $z, $w0_0, $z0_0) {

    (float) $a = -2.0 / ($w0_0 * $w0_0);
    (float) $b = -2.0 / ($z0_0 * $z0_0);
    return exp($a * ($x * $x + $y * $y) + $b * $z * $z);
}

function PeriodicBoundTest($X, $L) {
    if (abs($X) > $L) {
        $X = $X - 2 * $L * floor(($X + $L) / (2 * $L));
    }
    return $X;
}

function s() {
    (float) $w0 = 3e-7;    // in meters
    (float) $z0 = 9e-7;    // in meters
    (float) $F = 0.0;

    //% Modelling area
    (float) $R_Xb = 10 * $w0;
    (float) $R_Yb = 10 * $w0;
    (float) $R_Zb = 10 * $z0;

    // Volumes
    (float) $R_V = 8 * $R_Xb * $R_Yb * $R_Zb;

    //% Standard volume of FCS
    (float) $Veff = (1 + $F) * (1 + $F) * ( pow(pi(), 1.5) ) * $w0 * $w0 * $z0;

    //% Molecules

    (float) $Molecules_Diffusion = 2.8e-10;
    (float) $Molecules_Brightness = 100000; // in Hz
    (float) $Molecules_Neff = 0.01;

    (float) $Molecules_SimulMeanCount = $R_V * $Molecules_Neff / $Veff;
    (float) $Molecules_DiffusionTime = $w0 * $w0 / (4 * $Molecules_Diffusion);
    (float) $Molecules_Count = round($Molecules_SimulMeanCount);

    (int) $NumberOfEvents = 0;
    (int) $FNumberOfEvents = 0;

    (float) $Intensity = (1 + $F) * $Molecules_Brightness;

    (float) $InvI = 1.0 / $Intensity;
    (float) $PreviousEvent = 0;  //% For Brownian motion 
    (float) $CurrentEvent = 0;   //% For Brownian motion

    (float) $CurrIntensity = 0.0;
    (float) $StartTime = 0.0;
    (float) $EndTime = 1;

    //(float) $BB = B_function(0.0, 0.0, 0.0, $w0, $z0);
    for ($k = 0; $k < $Molecules_Count; $k++) {
        var_dump($k);
        (float) $Molecules_X = (2.0 * (float) rand() / (float) getrandmax() - 1.0) * $R_Xb;
        (float) $Molecules_Y = (2.0 * (float) rand() / (float) getrandmax() - 1.0) * $R_Yb;
        (float) $Molecules_Z = (2.0 * (float) rand() / (float) getrandmax() - 1.0) * $R_Zb;

        $PreviousEvent = $StartTime;  //  % Start generation from this moment of time
        $CurrentEvent = $PreviousEvent - $InvI * log((float) rand() / (float) getrandmax());  //% The first event of the flow

        if ($CurrentEvent < $EndTime) {

            while (true) {
                $PreviousEvent = $CurrentEvent;
                $CurrentEvent = $PreviousEvent - $InvI * log((float) rand() / (float) getrandmax());

                if ($CurrentEvent > $EndTime) {
                    break;
                }
                $BB = B_function($Molecules_X, $Molecules_Y, $Molecules_Z, $w0, $z0);
                $CurrIntensity = $Molecules_Brightness * $BB;
//  % Decimation of the flow

                if ((float) rand() / (float) getrandmax() * $Intensity < $CurrIntensity) {
                    $NumberOfEvents = $NumberOfEvents + 1;
                    $Events[$NumberOfEvents] = $PreviousEvent;
                    var_dump($PreviousEvent);
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
    var_dump($Events);
}
s();
?>