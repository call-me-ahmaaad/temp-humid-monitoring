<?php

namespace App\Services\MQTTSubscribe;

require_once __DIR__ . "/../Logger/SensorDataLogger.php";

use App\Services\Logger\SensorDataLogger;
use PhpMqtt\Client\MqttClient;
use App\Controllers\SensorDataController;

class SensorDataSubscribe
{
    private MqttClient $mqtt;
    private array $mqttConfig;
    private SensorDataController $controller;
    private SensorDataLogger $logger;

    public function __construct(MqttClient $mqtt, SensorDataController $controller, SensorDataLogger $logger)
    {
        $this->mqtt = $mqtt;
        $this->mqttConfig = require __DIR__ . "/../../../config/mqtt.php";
        
        $this->controller = $controller;
        $this->logger = $logger;
    }

    public function subscribe()
    {
        $this->mqtt->subscribe("sensor-data", function ($topic, $payload) {
            $this->logger->writeLog("Message received on $topic: $payload");
            $this->controller->msgHandle(json_decode($payload, true));
        });

        $this->mqtt->loop(true);
    }
}