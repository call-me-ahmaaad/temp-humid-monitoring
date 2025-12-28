<?php 

namespace App\Validators;

class MQTTValidator{
    public function sensorDataValidation($payload){
        $errors = [];
        
        if(!is_float($payload["temp"])){
            $errors[] = "Temperature must be float data type";
        }

        if($payload["temp"] == null){
            $errors[] = "Temperature cannot be null";
        }

        if(!is_float($payload["humid"])){
            $errors[] = "Humidity must be float data type";
        }

        if($payload["humid"] == null){
            $errors[] = "Humidity cannot be null";
        }

        return $errors;
    }
}