<?php

namespace App\Exceptions;

require_once __DIR__ . "/../Services/Logger/SensorDataLogger.php";

use Exception;
use App\Services\Logger\SensorDataLogger;

class SensorDataException extends Exception{
    public function __construct(string $message){
        parent::__construct($message);

        $errorLog = new SensorDataLogger();
        $errorLog->writeLog($message, "ERROR");
    }
}