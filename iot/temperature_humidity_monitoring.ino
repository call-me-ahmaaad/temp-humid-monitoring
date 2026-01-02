/* --------------------------------------------------
Author    : Muhammad
GitHub    : @call-me-ahmaaad
Instagram : @just.type_miguel
LinkedIn  : linkedin.com/in/muhammad-ahmad-9a1857266

Function:
  - Reads temperature and humidity via Modbus (RS485)
  - Sends data to the MQTT broker in JSON format
  - Illuminates the WiFi, MQTT, and sensor status indicator LEDs

-------------------------------------------------- */

// -------------------- LIBRARIES --------------------
#include <ESP8266WiFi.h>
#include <Ticker.h>
#include <PubSubClient.h>
#include <ModbusMaster.h>
#include <SoftwareSerial.h>
// -------------------- LIBRARIES --------------------

// -------------------- PIN CONFIGURATION --------------------
#define RX_PIN D2
#define TX_PIN D1

#define temp_ledRed D3
#define temp_ledGreen D4
#define temp_ledBlue D5

#define humid_ledRed D6
#define humid_ledGreen D7
#define humid_ledBlue D8
// -------------------- PIN CONFIGURATION --------------------

// -------------------- WIFI CREDENTIAL --------------------
const char* ssid = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";

IPAddress ipAddress;
String macAddress;
// -------------------- WIFI CREDENTIAL --------------------

// -------------------- MQTT CONFIGURATION --------------------
const char* mqttServer = "YOUR_MQTT_SERVER";
const int mqttPort = "YOUR_MQTT_PORT"; // Keep in mind that mqttPort data type is int

const char* topic = "YOUR_MQTT_TOPIC";
// -------------------- MQTT CONFIGURATION --------------------

// -------------------- EVENT HANDLERS & OBJECTS --------------------
Ticker wifiReconnectTimer;

WiFiClient espClient;
PubSubClient client(espClient);

SoftwareSerial RS485Serial(RX_PIN, TX_PIN);
ModbusMaster node;
// -------------------- EVENT HANDLERS & OBJECTS --------------------

// -------------------- TIMER SECTION --------------------
unsigned long previousMillis_getData = 0;
unsigned long interval_getData = 1000;

unsigned long previousMillis_printData = 0;
unsigned long interval_printData = 1000;

unsigned long previousMillis_sendData = 0;
unsigned long interval_sendData = 1000;
// -------------------- TIMER SECTION --------------------

// -------------------- GLOBAL VARIABLE INITIALIZATION --------------------
float temp = 0.00;
float humid = 0.00;
bool sensorStatus = false;
// -------------------- GLOBAL VARIABLE INITIALIZATION --------------------

// -------------------- FUNCTION TO CONNECT/RECONNECT TO WIFI --------------------
void connectToWiFi() {
  Serial.println("Connecting device to WiFi AP...");
  WiFi.begin(ssid, password);
}
// -------------------- FUNCTION TO CONNECT/RECONNECT TO WIFI --------------------

// -------------------- CALLBACK FUNCTION OF WIFI EVENTS --------------------
void onStationModeConnected(const WiFiEventStationModeConnected& evt) {
  Serial.println("Device successfully connected to WiFi AP!");
}

void onStationModeDisconnected(const WiFiEventStationModeDisconnected& evt) {
  Serial.println("Device disconnected from WiFi AP!");
  Serial.print("Reason: ");
  Serial.println(evt.reason);

  wifiReconnectTimer.once(5, connectToWiFi);
}

void onStationModeGotIP(const WiFiEventStationModeGotIP& evt) {
  Serial.print("WiFi SSID: ");
  Serial.println(WiFi.SSID());

  ipAddress = WiFi.localIP();
  Serial.print("IP Address: ");
  Serial.println(ipAddress);

  macAddress = WiFi.macAddress();
  macAddress.replace(":", "");
  macAddress.toUpperCase();
  Serial.print("MAC Address: ");
  Serial.println(macAddress);

  wifiReconnectTimer.detach();
}
// -------------------- CALLBACK FUNCTION OF WIFI EVENTS --------------------

// -------------------- FUNCTION TO PUBLISH MESSAGE TO MQTT TOPIC --------------------
void publishMqtt(const char* topic, const char* payload) {
  bool publish = client.publish(topic, payload);

  Serial.print("Publish Status  : ");
  Serial.println(publish ? "DELIVERED" : "FAILED");

  Serial.print("Payload         : ");
  Serial.println(publish ? payload : "-");
}
// -------------------- FUNCTION TO PUBLISH MESSAGE TO MQTT TOPIC --------------------

// -------------------- FUNCTION TO RECONNECT TO MQTT BROKER --------------------
void connectMqtt() {
  while (!client.connected()) {
    Serial.println("Attempting connection to MQTT...");
    String clientID = "ESP8266" + macAddress;

    if (client.connect(clientID.c_str())) {
      Serial.println("Connected to MQTT");
    } else {
      Serial.print("Failed. RC: ");
      Serial.print(client.state());
      Serial.print(" . Try again in 5 seconds");
      delay(5000);
    }
  }
}
// -------------------- FUNCTION TO RECONNECT TO MQTT BROKER --------------------

// -------------------- FUNCTION TO GET DATA FROM SENSOR --------------------
void getData() {
  uint8_t result;
  uint16_t data[2];

  result = node.readInputRegisters(0x0001, 2);

  if (result == node.ku8MBSuccess) {
    data[0] = node.getResponseBuffer(0);  // Temperature
    data[1] = node.getResponseBuffer(1);  // Humidity

    temp = data[0] / 10.00;
    humid = data[1] / 10.00;

    temp = roundf(temp * 100) / 100.00;
    humid = roundf(humid * 100) / 100.00;

    sensorStatus = true;
  } else {
    Serial.print("Modbus Error: ");
    Serial.println(result);

    sensorStatus = false;
  }
}
// -------------------- FUNCTION TO GET DATA FROM SENSOR --------------------

// -------------------- FUNCTION TO DO WHEN SENSOR STATUS IS OFF --------------------
void offModeSensor() {
  digitalWrite(temp_ledRed, HIGH);
  digitalWrite(temp_ledGreen, HIGH);
  digitalWrite(temp_ledBlue, HIGH);

  digitalWrite(humid_ledRed, HIGH);
  digitalWrite(humid_ledGreen, HIGH);
  digitalWrite(humid_ledBlue, HIGH);

  Serial.println("Couldn't get data because the sensor is OFF!");
}
// -------------------- FUNCTION TO DO WHEN SENSOR STATUS IS OFF --------------------

// -------------------- FUNCTION TO PRINT DATA FROM SENSOR --------------------
void printData() {
  if (temp >= 75) {
    digitalWrite(temp_ledRed, LOW);
    digitalWrite(temp_ledGreen, HIGH);
    digitalWrite(temp_ledBlue, HIGH);
  } else if (temp >= 35 && temp < 75) {
    digitalWrite(temp_ledRed, LOW);
    digitalWrite(temp_ledGreen, LOW);
    digitalWrite(temp_ledBlue, HIGH);
  } else if (temp >= 0) {
    digitalWrite(temp_ledRed, HIGH);
    digitalWrite(temp_ledGreen, LOW);
    digitalWrite(temp_ledBlue, HIGH);
  } else {
    Serial.println("Temperature out of range");
  }

  if (humid >= 75) {
    digitalWrite(humid_ledRed, LOW);
    digitalWrite(humid_ledGreen, HIGH);
    digitalWrite(humid_ledBlue, HIGH);
  } else if (humid >= 35 && humid < 75) {
    digitalWrite(humid_ledRed, LOW);
    digitalWrite(humid_ledGreen, LOW);
    digitalWrite(humid_ledBlue, HIGH);
  } else if (humid >= 0) {
    digitalWrite(humid_ledRed, HIGH);
    digitalWrite(humid_ledGreen, LOW);
    digitalWrite(humid_ledBlue, HIGH);
  } else {
    Serial.println("Temperature out of range");
  }

  Serial.print("Temperature     : ");
  Serial.print(temp);
  Serial.println("°C");

  Serial.print("Humidity        : ");
  Serial.print(humid);
  Serial.println("%");
  Serial.println("============================================================");
}
// -------------------- FUNCTION TO PRINT DATA FROM SENSOR --------------------

/* ---------------------------------------------------------------------------
  SETUP: Runs once at boot time
  - Serial, RS485, pin, WiFi, and MQTT initialization
--------------------------------------------------------------------------- */
void setup() {
  Serial.begin(115200);

  RS485Serial.begin(9600);
  node.begin(1, RS485Serial);  // Modbus Slave ID = 1. Might be different with your, so please check again.

  Serial.println("Starting.....");

  pinMode(temp_ledRed, OUTPUT);
  pinMode(temp_ledGreen, OUTPUT);
  pinMode(temp_ledBlue, OUTPUT);

  pinMode(humid_ledRed, OUTPUT);
  pinMode(humid_ledGreen, OUTPUT);
  pinMode(humid_ledBlue, OUTPUT);

  WiFi.disconnect(true);
  WiFi.mode(WIFI_STA);

  WiFi.onStationModeConnected(onStationModeConnected);
  WiFi.onStationModeDisconnected(onStationModeDisconnected);
  WiFi.onStationModeGotIP(onStationModeGotIP);

  Serial.println("Starting connection to WiFi AP...");
  connectToWiFi();

  Serial.println("Starting device connection to MQTT...");
  client.setServer(mqttServer, mqttPort);
}

/* ---------------------------------------------------------------------------
  LOOP: Runs repeatedly
  - Checks MQTT connection
  - Reads sensor every 1 second
  - Publishes data every 1 seconds
--------------------------------------------------------------------------- */
void loop() {
  unsigned long currentMillis = millis();

  if (!client.connected()) {
    connectMqtt();
  }
  client.loop();

  // -------------------- GET DATA FROM SENSOR -------------------- 
  if (currentMillis - previousMillis_getData >= interval_getData) {
    getData();
    previousMillis_getData = currentMillis;
  }
  // -------------------- GET DATA FROM SENSOR -------------------- 

  // -------------------- SEND DATA TO MQTT TOPIC -------------------- 
  if (currentMillis - previousMillis_sendData >= interval_sendData) {
    if (sensorStatus) {
      char payload[64];

      snprintf(
        payload,
        sizeof(payload),
        "{\"temp\":%.2f, \"humid\":%.2f}",
        temp, humid
      );

      publishMqtt(topic, payload);

      previousMillis_sendData = currentMillis;
    } else {
      Serial.println("Skipping publish process because sensor is OFF!");
    }
  }
  // -------------------- SEND DATA TO MQTT TOPIC -------------------- 

  // -------------------- PRINT TEMPERATURE AND HUMIDITY --------------------
  if (currentMillis - previousMillis_printData >= interval_printData) {
    if (sensorStatus) {
      printData();
    } else {
      offModeSensor();
    }

    previousMillis_printData = currentMillis;
  }
  // -------------------- PRINT TEMPERATURE AND HUMIDITY --------------------
}
