<?php 

namespace App\Controllers;

require_once __DIR__ . "/../Validators/MQTTValidator.php";

use App\Validators\MQTTValidator;

class MQTTController{
    public function msgHandle($topic, $payload){
        $msg = json_decode($payload, true);

        $validator = new MQTTValidator();
        $errors = $validator->sensorDataValidation($msg);

        if(!empty($errors)){
            foreach($errors as $error){
                echo "[ERROR] $error" . PHP_EOL;
            }

            return $errors;
        }

        echo "Message Received on topic: $topic". PHP_EOL;
        echo "Validated Sensor Data: " . PHP_EOL;
        foreach($msg as $key => $value){
            echo "$key = $value" . PHP_EOL;
        }
    }
}