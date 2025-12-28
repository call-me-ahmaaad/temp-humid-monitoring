<?php

namespace App\Exceptions;

require_once __DIR__ . "/../Services/SensorDataLogger.php";

use Exception;
use App\Services\SensorDataLogger;

class SensorDataException extends Exception{
    public function __construct(string $message, int $code){
        parent::__construct($message, $code);

        $errorLog = new SensorDataLogger();
        $errorLevel = "ERROR";
        $errorLog->writeLog($message, $errorLevel, $code);
    }
}