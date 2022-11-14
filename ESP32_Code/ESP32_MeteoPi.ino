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
#define VERSION 03

Adafruit_BMP085 bmp;
DHT dht(DHT_PIN, DHT_TYPE);
BH1750 lightMeter;
Adafruit_CCS811 ccs;

// Replace with your network credentials
const char* ssid     = "<ENTER YOUR SSID>";
const char* password = "<ENTER YOUR WLAN PASSWORD>";
const String server_ip = "<ENTER YOUR WEBSERVER DEVICE'S IP>";
String clientMacAddressRefference = "<ENTER MAC-ADRESS OF YOUR CLIENT>";

float temperature = NULL;
float humidity = NULL;
float heatIndex = NULL;
double pressure = NULL;
uint16_t lux = NULL;
float eco2, tvoc = NULL;

//Select which sensor are connected and should be read!
bool sensor_dht = true;
bool sensor_bmp = false;
bool sensor_lightmeter = false;
bool sensor_css = false;


void setup() {
  Serial.begin(9600);

  if (sensor_dht){
    dht.begin();
  }

  if(sensor_bmp){
    bmp.begin();
  }

  if(sensor_lightmeter){
    lightMeter.begin();
  }
  
  if(sensor_css){
    if (!ccs.begin()) {
      Serial.println("Failed to start sensor! Please check your wiring.");
    }
  
    while (!ccs.available());
  }
  
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
  if(sensor_dht){humidity = dht.readHumidity();}
  if(sensor_dht){temperature = dht.readTemperature();}
  if(sensor_dht){heatIndex = dht.computeHeatIndex(temperature, humidity, false);}
  //1 hPa = 100 Pa = 1 mbar = 0.001 bar
  if(sensor_bmp){pressure = bmp.readPressure() / 100.0;} //mbar
  if(sensor_lightmeter){lux = lightMeter.readLightLevel();}
  if(sensor_css){
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
    http.begin("http://"+server_ip+"/meteopi/uploadData.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");


    String postString = "";
    if(sensor_dht){
      postString += "temperature=" + String(temperature)
                 + "&humidity=" + String(humidity)
                 + "&heatIndex=" + String(heatIndex);
    }
    if(sensor_bmp){
        postString += "&pressure=" + String(pressure);
    } 
    if(sensor_lightmeter){
      postString += "&lux=" + String(lux);
    }
    if(sensor_css){
      postString  += "&eco2=" + String(eco2)
                  + "&tvoc=" + String(tvoc);
    }

    postString += "&clientMacAddressRefference=" + String(clientMacAddressRefference);

    int httpResponseCode = http.POST(postString);
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
