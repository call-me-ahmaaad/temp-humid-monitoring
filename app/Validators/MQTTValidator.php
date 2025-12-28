<?php 

namespace App\Validators;

require_once __DIR__ . "/../Services/Logger/SensorDataLogger.php";

use App\Services\Logger\SensorDataLogger;

class MQTTValidator{
    public function sensorDataValidation($payload): array{
        $errors = [];

        $logger = new SensorDataLogger();
        
        if(!is_float($payload["temp"])){
            $errorMsg = "Temperature must be float data type";
            $errors[] = $errorMsg;
            $logger->writeLog($errorMsg, "ERROR");
        }

        if($payload["temp"] === null){
            $errorMsg = "Temperature cannot be null";
            $errors[] = $errorMsg;
            $logger->writeLog($errorMsg, "ERROR");
        }

        if(!is_float($payload["humid"])){
            $errorMsg = "Humidity must be float data type";
            $errors[] = $errorMsg;
            $logger->writeLog($errorMsg, "ERROR");
        }

        if($payload["humid"] === null){
            $errorMsg = "Humidity cannot be null";
            $errors[] = $errorMsg;
            $logger->writeLog($errorMsg, "ERROR");
        }

        return $errors;
    }
}