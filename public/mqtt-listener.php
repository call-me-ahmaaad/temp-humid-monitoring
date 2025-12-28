<?php 
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../infrastructure/MQTT/MQTTSubscribe.php";
require_once __DIR__ . "/../app/Controllers/MQTTController.php";
require_once __DIR__ . "/../app/Services/SensorDataLogger.php";

use App\Infrastructure\MQTT\MQTTSubscribe;
use App\Controllers\MQTTController;
use App\Services\SensorDataLogger;

$msgHandler = new MQTTController();
$sensorDataLogger = new SensorDataLogger();
$mqtt = new MQTTSubscribe($msgHandler, $sensorDataLogger);
$mqtt->listen();