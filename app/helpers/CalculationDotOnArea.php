<?php

namespace App\helpers;


use Illuminate\Support\Facades\Log;

class CalculationDotOnArea
{
    /**
     * Create a new class instance.
     */
    private $long = 16.75380;
    private $lat = -93.11591;
    private $center = [
        "x" => 16.75380,
        "y" => -93.11591
    ];
    private $radio = 20;
    private $area;

    public function __construct()
    {
        //
    }

    public function areaDot()
    {
        return pi() * pow($this->radio, 2);
    }

    /**
     * Si regresa 1 quiere decir que ya esta fuera del punto
     */
    public function puntos()
    {

        // $puntox =  16.75389;
        // $puntoy =  -93.11594;
         $puntox =  16.75838272286179;
         $puntoy =  -93.12558931160086;
        $RadianPuntoAy = ($this->lat * pi()) / 180;
        $RadianPuntoAx = ($this->long * pi()) / 180;
        $RadianPuntoBy = ($puntoy * pi()) / 180;
        $RadianPuntoBx = ($puntox * pi()) / 180;

        $RADIOTIERRA = 6371;

        $distancia = acos(sin($RadianPuntoAy) * sin($RadianPuntoBy) + cos($RadianPuntoAy) * cos($RadianPuntoBy) * cos($RadianPuntoAx - $RadianPuntoBx)) * $RADIOTIERRA;
        return $distancia;
    }

    public function calcularDistancia(){
        // $x = 16.7538;
        // $y =  -93.11594;

        $x =  16.75397;
        $y =  -93.11565;
        $center_x = $this->center["x"];
        $center_y = $this->center["y"];

        $degrees = rad2deg(acos((sin(deg2rad($center_y))*sin(deg2rad($y))) + (cos(deg2rad($center_y))*cos(deg2rad($y))*cos(deg2rad($center_x-$x)))));

        return  $degrees * 111.13384;
}

    public function toRadian($value)
    {
        return ($value * pi()) / 180;
    }

    /**
     *   
     */
    public function formula(){

        // 16.743945187414404, -93.10940970154604
        $x =  16.743945187414404; //$this->long;
        $y =  -93.10940970154604;//$this->lat;
        $center_x = $this->center["x"];
        $center_y = $this->center["y"];

        $r = sqrt(20);
        Log::info($r);
        return  sqrt(pow(($x - $center_x),2) + pow(($y - $center_y), 2));
        // return sqrt((pow(($center_x - $x), 2) + pow(($center_y - $y), 2)));
        // return sqrt(pow(($y - $center_y),2) + pow(($x - $center_x), 2));
    }

    public function isValid($x,$y){
        $x = $x;
        $y = $y;

        $r = 20;
        $center_x = $this->center["x"];
        $center_y = $this->center["y"];

        $formula = pow((($x - $center_x)), 2) + pow((($y - $center_y)), 2);
        $r_c = pow($r, 2);
        Log::info($r_c);
        Log::info($formula);

        return $formula;
    }
}
