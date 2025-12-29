<?php

namespace App\Infrastructure\MQTT;

use Exception;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;
use App\Services\Logger\SensorDataLogger;

class MQTTConnection
{
    private array $mqttConfig;

    private SensorDataLogger $logger;

    public function __construct(SensorDataLogger $logger){
        $this->mqttConfig = require __DIR__ . "/../../config/mqtt.php";
        $this->logger = $logger;
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

        try{
            $mqtt->connect($connectionSettings, true);

            $this->logger->writeLog("Successfully connected to MQTT");
            echo "[INFO] Successfully connected to MQTT" . PHP_EOL;
        }catch(Exception $e){
            $this->logger->writeLog("Successfully connected to MQTT","ERROR");
            echo "[ERROR] {$e->getMessage()}" . PHP_EOL;
        }
        
        return $mqtt;
    }
}