<?php 
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../infrastructure/MQTT/MQTTSubscribe.php";
require_once __DIR__ . "/../app/Controllers/MQTTController.php";

use App\Infrastructure\MQTT\MQTTSubscribe;
use App\Controllers\MQTTController;

$msgHandler = new MQTTController();
$mqtt = new MQTTSubscribe($msgHandler);
$mqtt->listen();