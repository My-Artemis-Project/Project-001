/*
  Rui Santos
  Complete project details at Complete project details at https://RandomNerdTutorials.com/esp32-http-get-post-arduino/

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files.

  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
*/

#include <WiFi.h>
#include <HTTPClient.h>
#include <DHT.h>

#define SOUND_SPEED 0.034
#define DHT_SENSOR_PIN 17
#define DHT_SENSOR_TYPE DHT11

DHT dht_sensor(DHT_SENSOR_PIN, DHT_SENSOR_TYPE);

// wifi auth
const char* ssid = "Artemis";
const char* password = "antihack22";

// url store data
const char* tinggiBakAirAPI = "http://34.101.128.49/api/store/data/tinggi_bak_air";
const char* tempAPI = "http://34.101.128.49/api/store/data/suhu";
const char* kelembabanAPI = "http://34.101.128.49/api/store/data/kelembaban";
const char* phAPI = "http://34.101.128.49/api/store/data/ph";

// delay per send data
unsigned long lastTime = 0;
unsigned long timerDelay = 5000;

// ultrasonic 1
const int ultrasonic_1_trigger = 18;
const int ultrasonic_1_echo = 19;
long duration;
float distanceCm;

// PH
const int ph_pin = 34;
float PH4 = 3.55;
float PH7 = 3.19;

void setup() {
  Serial.begin(115200);

  // trigger pin untuk ultrasonic 1
  pinMode(ultrasonic_1_trigger, OUTPUT);
  // echo pin untuk ultrasonic 1
  pinMode(ultrasonic_1_echo, INPUT);
  // ph
  // pinMode(ph_pin, INPUT);
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  Serial.println("Timer set to 5 seconds (timerDelay variable), it will take 5 seconds before publishing the first reading.");

  dht_sensor.begin();
}

int ultrasonic(int trigger, int echo) {
  // Clears the trigger
  digitalWrite(trigger, LOW);
  delayMicroseconds(2);
  // Sets the trigger on HIGH state for 10 micro seconds
  digitalWrite(trigger, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigger, LOW);

  // Reads the echo, returns the sound wave travel time in microseconds
  duration = pulseIn(echo, HIGH);

  // Calculate the distance
  distanceCm = duration * SOUND_SPEED / 2;

  return distanceCm;
}

float temp() {
  float tempC = dht_sensor.readTemperature();
  if (isnan(tempC)) {
    Serial.println("Failed to read from DHT (temp) sensor!");
  } else {
    return tempC;
  }
  return 0;
}

float humi() {
  float humi = dht_sensor.readHumidity();
  if (isnan(humi)) {
    Serial.println("Failed to read from DHT (humi) sensor!");
  } else {
    return humi;
  }
  return 0;
}

float sensor_ph() {
  int nilai_analog_ph = analogRead(ph_pin);
  // Serial.println("PH : " + String(nilai_analog_ph));

  double tegangan_ph = 3.3 / 1024.0 * nilai_analog_ph;
  float ph_step = (PH4 - PH7) / 3;
  float po = 7.00 + ((PH7 - tegangan_ph) / ph_step);
  return po;
}

void postData(String host, String data) {
  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;
    HTTPClient http;

    // deklarasi awal untuk kirim data
    http.begin(client, host);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    // kirim data dengan metode POST
    int httpResponseCode = http.POST(data);


    Serial.println("----------------------------------------------------");
    Serial.println("Host : " + host);
    Serial.println("Data : " + data);
    Serial.println("Response code : " + String(httpResponseCode));
    Serial.println("----------------------------------------------------");
    Serial.println();

    // Free resources
    http.end();
  } else {
    Serial.println("WiFi Disconnected");
  }
}
void loop() {
  // kirim data per timerDelay
  if ((millis() - lastTime) > timerDelay) {

    // get ultrasonic
    int ultrasonic_1 = ultrasonic(ultrasonic_1_trigger, ultrasonic_1_echo);
    String data = "value=" + String(ultrasonic_1);
    postData(tinggiBakAirAPI, data);

    float tempData = temp();
    String tempDataString = "value=" + String(tempData);
    postData(tempAPI, tempDataString);

    float kelambabanData = humi();
    String kelambabanDataString = "value=" + String(kelambabanData);
    postData(kelembabanAPI, kelambabanDataString);

    float phData = sensor_ph();
    String phDataString = "value="+ String(phData);
    postData(phAPI, phDataString);

    lastTime = millis();
  }
}