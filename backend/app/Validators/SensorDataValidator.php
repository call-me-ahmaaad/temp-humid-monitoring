<?php 

namespace App\Validators;

use App\Services\Logger\SensorDataLogger;

class SensorDataValidator{
    private SensorDataLogger $logger;

    public function __construct(SensorDataLogger $logger){
        $this->logger = $logger;
    }

    public function sensorDataValidation($payload): array{
        $errors = [];
        
        if(!is_float($payload["temp"])){
            $errorMsg = "Temperature must be float data type";
            $errors[] = $errorMsg;
            $this->logger->writeLog($errorMsg, "ERROR");
        }

        if($payload["temp"] === null){
            $errorMsg = "Temperature cannot be null";
            $errors[] = $errorMsg;
            $this->logger->writeLog($errorMsg, "ERROR");
        }

        if(!is_float($payload["humid"])){
            $errorMsg = "Humidity must be float data type";
            $errors[] = $errorMsg;
            $this->logger->writeLog($errorMsg, "ERROR");
        }

        if($payload["humid"] === null){
            $errorMsg = "Humidity cannot be null";
            $errors[] = $errorMsg;
            $this->logger->writeLog($errorMsg, "ERROR");
        }

        return $errors;
    }
}