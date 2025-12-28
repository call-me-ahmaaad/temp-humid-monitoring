<?php 
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../infrastructure/MQTT/MQTTConnection.php";
require_once __DIR__ . "/../app/Services/MQTTSubscribe/SensorDataSubscribe.php";
require_once __DIR__ . "/../app/Controllers/SensorDataController.php";

use App\Controllers\SensorDataController;
use App\Infrastructure\MQTT\MQTTConnection;
use App\Services\MQTTSubscribe\SensorDataSubscribe;

$mqttConnect = new MQTTConnection();
$mqtt = $mqttConnect->connect();

$controller = new SensorDataController();
$mqttSubscribe = new SensorDataSubscribe($mqtt, $controller);
$mqttSubscribe->subscribe();