<?php 

namespace App\Infrastructure\MQTT;

use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;


class MQTTSubscribe{

    private array $mqttConfig;

    public function __construct(){
        $this->mqttConfig = require __DIR__ . "/../../config/mqtt.php";
    }

    public function listen(){
        $mqtt = $this->connect();

        foreach($this->mqttConfig["topics"] as $topic){
            $mqtt->subscribe($topic, function($topic, $payload){
                echo "Receive a message from $topic: $payload" . PHP_EOL;
            });
        }

        $mqtt->loop(true);
    }

    private function connect(): MqttClient{
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