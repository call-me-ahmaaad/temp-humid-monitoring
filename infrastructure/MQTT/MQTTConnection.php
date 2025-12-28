<?php

namespace App\Infrastructure\MQTT;

require_once __DIR__ . "/../../app/Services/Logger/SensorDataLogger.php";

use Exception;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;
use App\Services\Logger\SensorDataLogger;

class MQTTConnection
{
    private array $mqttConfig;

    public function __construct(){
        $this->mqttConfig = require __DIR__ . "/../../config/mqtt.php";
    }

    public function connect(): MqttClient
    {
        $server = $this->mqttConfig["host"];
        $port = $this->mqttConfig["port"];
        $clientId = $this->mqttConfig["clientId"];

        $connectionSettings = (new ConnectionSettings)
            ->setUsername($this->mqttConfig["username"])
            ->setPassword($this->mqttConfig["password"])
            ->setConnectTimeout($this->mqttConfig["keepAlive"]);

        $mqtt = new MqttClient($server, $port, $clientId);
        $logger = new SensorDataLogger();

        try{
            $mqtt->connect($connectionSettings, true);

            $logger->writeLog("Successfully connected to MQTT");
            echo "[INFO] Successfully connected to MQTT" . PHP_EOL;
        }catch(Exception $e){
            $logger->writeLog("Successfully connected to MQTT","ERROR");
            echo "[ERROR] {$e->getMessage()}" . PHP_EOL;
        }
        
        return $mqtt;
    }
}