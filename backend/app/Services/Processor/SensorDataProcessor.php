<?php 

namespace App\Services;

use App\Exceptions\SensorDataException;

class SensorDataProcessor{
    public function processData(float $temp, float $humid){
        if($temp < -50 or $temp > 100){
            throw new SensorDataException(
                "Temperature out of range [\"temp\": {$temp}, \"humid\": {$humid}]");
        }

        if($humid < 0 or $humid > 100){
            throw new SensorDataException(
                "Humidity out of range [\"temp\": {$temp}, \"humid\": {$humid}]");
        }
    }
}