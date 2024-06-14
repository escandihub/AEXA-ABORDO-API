<?php

namespace App\helpers;

use Illuminate\Support\Facades\Log;

class formula
{

    public function __construct()
    {
        if ($this->isPointWithinRadius(16.7546, -93.1155, 16.753895975200898, -93.11691013528356, 0.005)) {
            Log::info("El punto está dentro del radio");
            return "El punto está dentro del radio.";
        } else {
            Log::info("El punto está Fuera del radio");
            return "El punto está fuera del radio.";
        }
    }

function haversineDistance($lat1, $lon1, $lat2, $lon2) {
    // Radio de la Tierra en kilómetros
    $earth_radius = 6371;

    // Convertir las latitudes y longitudes de grados a radianes
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    // Diferencias de las coordenadas
    $dlat = $lat2 - $lat1;
    $dlon = $lon2 - $lon1;

    // Fórmula de Haversine
    $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlon/2) * sin($dlon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $distance = $earth_radius * $c;

    return $distance;
}

function isPointWithinRadius($centerLat, $centerLon, $pointLat, $pointLon, $radius) {
    $distance = $this->haversineDistance($centerLat, $centerLon, $pointLat, $pointLon);
    return $distance <= $radius;
}

}

/*
$centerLat = 16.7546, ; // Latitud del centro (por ejemplo, Nueva York)
$centerLon = -93.1155; // Longitud del centro
$pointLat = 16.7548; // Latitud del punto (por ejemplo, algún punto en Nueva York)
$pointLon = -93.1156; // Longitud del punto
$radius = 10; // 
*/