<?php 

namespace App\Services;

class SensorDataLogger{
    private $logFile;

    public function __construct(){
        $this->logFile = __DIR__ . "/../../log/sensorData.log";

        $folder = dirname($this->logFile);
        if(!is_dir($folder)){
            mkdir($folder, 0777, true);
        }
        
    }
    public function writeLog(string $logMsg, ?int $errorCode, string $level = "INFO"){
        date_default_timezone_set("Asia/Jakarta");

        $timestamp = date("Y-m-d H:i:s");
        $code = !is_null($errorCode) ? "[$errorCode]" : "";
        $logLine = "[$timestamp][$level]{$code} $logMsg\n";

        file_put_contents($this->logFile, $logLine, FILE_APPEND);
    }
}