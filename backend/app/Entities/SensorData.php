<?php 

namespace App\Entities;

class SensorData{
    private float $temp;
    private float $humid;
    
    public function __construct(float $temp, float $humid){
        $this->temp = $temp;
        $this->humid = $humid;
    }

    public function getTemp(){
        return $this->temp;
    }

    public function getHumid(){
        return $this->humid;
    }
}