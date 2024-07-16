<?php

namespace App;
use Illuminate\Support\Facades\Log;

class PointInCircle
{

    #Lat,Lon: 16.75387,-93.11580
    private $center = [
        "x" => 16.75387,
        "y" => -93.11580
    ];

    // Radio de la Tierra en kilómetros

    private $earthRadius = 6371;
    private  $earthRadiusMeter = 6371000;

    public function __construct()
    {
        //
    }

    public function calculate($x, $y)
    {
        // Conversión de grados a radianes
        $dLat = deg2rad($this->center["x"] - $x);
        $dLng = deg2rad($this->center["y"] - $y);

        // Fórmula de Haversine para calcular la distancia
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($x)) * cos(deg2rad($this->center["x"])) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $this->earthRadiusMeter * $c;

        Log::info($distance);

        return $distance <= 20;
    }
}
