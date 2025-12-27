<?php 
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../infrastructure/MQTT/MQTTSubscribe.php";

use App\Infrastructure\MQTT\MQTTSubscribe;

$mqtt = new MQTTSubscribe();
$mqtt->listen();