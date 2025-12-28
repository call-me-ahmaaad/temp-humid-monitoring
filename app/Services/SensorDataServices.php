<?php 

namespace App\Services;

use App\Exceptions\SensorDataException;

class SensorDataServices{
    public function createSensorData(float $temp, float $humid){
        if($temp < -50 or $temp > 100){
            throw new SensorDataException(
                "Temperature out of range [\"temp\": {$temp}, \"humid\": {$humid}]", 
                103);
        }

        if($humid < 0 or $humid > 100){
            throw new SensorDataException(
                "Humidity out of range [\"temp\": {$temp}, \"humid\": {$humid}]", 
                203);
        }

        $this->printSensorData($temp, $humid);
    }

    public function printSensorData(float $temp, float $humid){
        echo "[INFO] Final Data: [\"temp\": $temp, \"humid\": $humid]" . PHP_EOL;
    }
}