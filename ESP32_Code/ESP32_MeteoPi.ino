#include <MySQL_Connection.h>
#include <MySQL_Cursor.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <DHT.h>
#include <Wire.h>
#include <Adafruit_BMP085.h>
#include <BH1750.h>
#include <Adafruit_CCS811.h>

#define DHT_TYPE DHT11
#define DHT_PIN 25
#define VERSION 02

Adafruit_BMP085 bmp;
DHT dht(DHT_PIN, DHT_TYPE);
BH1750 lightMeter;
Adafruit_CCS811 ccs;

// Replace with your network credentials
const char* ssid     = "SaSaPeKi8693";
const char* password = "aEyfw5p5vsYx";

float temperature = 0.0f;
float humidity = 0.0f;
float heatIndex = 0.0f;
double pressure = 0.0;
uint16_t lux = 0;
float eco2, tvoc = 0.0f;
String clientMacAddressRefference = "";

void setup() {
  Serial.begin(9600);

  dht.begin();
  bmp.begin();
  lightMeter.begin();

  if (!ccs.begin()) {
    Serial.println("Failed to start sensor! Please check your wiring.");
  }

  while (!ccs.available());
  ConnectToWifi();
}

void ConnectToWifi()
{
  WiFi.begin(ssid, password);

  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
  Serial.print("ESP32 MAC Address: ");
  Serial.println(WiFi.macAddress());

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
  }
}

void loop() {
  humidity = dht.readHumidity();
  temperature = dht.readTemperature();
  heatIndex = dht.computeHeatIndex(temperature, humidity, false);
  //1 hPa = 100 Pa = 1 mbar = 0.001 bar
  pressure = bmp.readPressure() / 100.0; //mbar
  lux = lightMeter.readLightLevel();
  if (ccs.available()) {
    if (!ccs.readData()) {
      Serial.println("Data read sucessfully!");
      eco2 = ccs.geteCO2();
      tvoc = ccs.getTVOC();
    } else {
      //If error ocurs, previous value is taken.
      Serial.println("ERROR!");
    }
  }else {
    Serial.println("Sensor not available!");
  }

  Serial.print("humidity = ");
  Serial.println(humidity);
  Serial.print("temperature = ");
  Serial.println(temperature);
  Serial.print("heatIndex = ");
  Serial.println(heatIndex);
  Serial.print("pressure = ");
  Serial.println(pressure);
  Serial.print("lux = ");
  Serial.println(lux);
  Serial.print("eco2 = ");
  Serial.println(eco2);
  Serial.print("tvoc = ");
  Serial.println(tvoc);

  //Check WiFi connection status
  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("WiFi Connected. Start Upload");

    //Open a connection to the server
    HTTPClient http;
    http.begin("http://192.168.0.129/meteopi/uploadData.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    clientMacAddressRefference = "98:F4:AB:62:0B:5C";

    int httpResponseCode = http.POST("temperature=" + String(temperature)
                                     + "&humidity=" + String(humidity)
                                     + "&heatIndex=" + String(heatIndex)
                                     + "&pressure=" + String(pressure)
                                     + "&lux=" + String(lux)
                                     + "&eco2=" + String(eco2)
                                     + "&tvoc=" + String(tvoc)
                                     + "&clientMacAddressRefference=" + String(clientMacAddressRefference));
    Serial.print("HTTP response code:");
    if (httpResponseCode > 0) {
      //check for a return code - This is more for debugging.
      String response = http.getString();

      Serial.println(httpResponseCode);
      Serial.println(response);
    }
    else {
      Serial.print("Error on sending post");
      Serial.println(httpResponseCode);
    }
    //closde the HTTP request.
    http.end();
  }
  else {
    Serial.println("WiFi Disconnected");
    Serial.println("Retry to connect...");
    ConnectToWifi();
  }
  delay(300000);
}
