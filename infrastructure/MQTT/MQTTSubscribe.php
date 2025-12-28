<?php

namespace App\Infrastructure\MQTT;

use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;
use App\Controllers\MQTTController;

class MQTTSubscribe
{

    private array $mqttConfig;
    private MqttController $mqttController;

    public function __construct(MqttController $mqttController)
    {
        $this->mqttController = $mqttController;
        $this->mqttConfig = require __DIR__ . "/../../config/mqtt.php";
    }

    public function listen()
    {
        $mqtt = $this->connect();
        $controller = $this->mqttController;

        foreach ($this->mqttConfig["topics"] as $topic) {
            $mqtt->subscribe($topic, function ($topic, $payload) use ($controller) {
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

        $connectionSettings = (new ConnectionSettings)
            ->setUsername($this->mqttConfig["username"])
            ->setPassword($this->mqttConfig["password"])
            ->setConnectTimeout($this->mqttConfig["keepAlive"]);

        $mqtt = new MqttClient($server, $port, $clientId);
        $mqtt->connect($connectionSettings, true);

        return $mqtt;
    }
}