<?php

namespace App\Services\MQTTSubscribe;

use PhpMqtt\Client\MqttClient;
use App\Controllers\SensorDataController;

class SensorDataSubscribe
{
    private MqttClient $mqtt;
    private array $mqttConfig;
    private SensorDataController $controller;

    public function __construct(MqttClient $mqtt, SensorDataController $controller)
    {
        $this->mqtt = $mqtt;
        $this->controller = $controller;
        $this->mqttConfig = require __DIR__ . "/../../../config/mqtt.php";
    }

    public function subscribe()
    {
        foreach ($this->mqttConfig["topics"] as $topic) {
            $this->mqtt->subscribe($topic, function ($topic, $payload) {
                $this->controller->msgHandle(json_decode($payload, true));
            });
        }

        $this->mqtt->loop(true);
    }
}