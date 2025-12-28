<?php

namespace App\Infrastructure\MQTT;

use Exception;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;
use App\Controllers\MQTTController;
use App\Services\SensorDataLogger;

class MQTTSubscribe
{
    private array $mqttConfig;
    private MqttController $mqttController;
    private SensorDataLogger $sensorDataLogger;

    public function __construct(MqttController $mqttController, SensorDataLogger $sensorDataLogger)
    {
        $this->mqttController = $mqttController;
        $this->sensorDataLogger = $sensorDataLogger;
        $this->mqttConfig = require __DIR__ . "/../../config/mqtt.php";
    }

    public function listen()
    {
        $mqtt = $this->connect();
        $controller = $this->mqttController;
        $logger = $this->sensorDataLogger;

        foreach ($this->mqttConfig["topics"] as $topic) {
            $mqtt->subscribe($topic, function ($topic, $payload) use ($controller, $logger) {
                $msgLog = "Message received on topic $topic. Payload: $payload";
                $logger->writeLog($msgLog, null);
                
                $controller->msgHandle($topic, $payload);
            });
        }

        $mqtt->loop(true);
    }

    private function connect(): MqttClient
    {
        $server = $this->mqttConfig["host"];
        $port = $this->mqttConfig["port"];
        $clientId = $this->mqttConfig["clientId"];

        $logger = $this->sensorDataLogger;

        $connectionSettings = (new ConnectionSettings)
            ->setUsername($this->mqttConfig["username"])
            ->setPassword($this->mqttConfig["password"])
            ->setConnectTimeout($this->mqttConfig["keepAlive"]);

        $mqtt = new MqttClient($server, $port, $clientId);

        try{
            $mqtt->connect($connectionSettings, true);

            echo "[INFO] Successfully connected to MQTT" . PHP_EOL;
            $logger->writeLog("Successfully connected to MQTT", null);
        }catch(Exception $e){
            echo "[ERROR] {$e->getMessage()}" . PHP_EOL;
            $logger->writeLog($e->getMessage(), $e->getCode(), "ERROR");
        }
        
        return $mqtt;
    }
}