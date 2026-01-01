<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../infrastructure/MQTT/MQTTConnection.php";
require_once __DIR__ . "/../app/Services/MQTTSubscribe/SensorDataSubscribe.php";
require_once __DIR__ . "/../app/Controllers/SensorDataController.php";
require_once __DIR__ . "/../infrastructure/Database/DBConnection.php";
require_once __DIR__ . "/../app/Services/Processor/SensorDataProcessor.php";
require_once __DIR__ . "/../app/Validators/SensorDataValidator.php";
require_once __DIR__ . "/../app/Services/Logger/SensorDataLogger.php";
require_once __DIR__ . "/../app/Repositories/SensorDataTable.php";

use App\Controllers\SensorDataController;
use App\Infrastructure\MQTT\MQTTConnection;
use App\Repositories\SensorDataTable;
use App\Services\Logger\SensorDataLogger;
use App\Services\MQTTSubscribe\SensorDataSubscribe;
use App\Infrastructure\Database\DBConnection;
use App\Services\SensorDataProcessor;
use App\Validators\SensorDataValidator;

$logger = new SensorDataLogger();

$dbConnect = new DBConnection($logger);
$pdo = $dbConnect->connect();

$repository = new SensorDataTable(
    $pdo,
    $logger
);
$processor = new SensorDataProcessor();
$validator = new SensorDataValidator($logger);

$controller = new SensorDataController(
    $validator,
    $processor,
    $repository
);

$mqttConnect = new MQTTConnection($logger);
$mqtt = $mqttConnect->connect();

$mqttSubscribe = new SensorDataSubscribe(
    $mqtt,
    $controller,
    $logger
);
$mqttSubscribe->subscribe();