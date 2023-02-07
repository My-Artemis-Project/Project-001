#include <WiFi.h>
#include <HTTPClient.h>
#include <DHT.h>

#define SOUND_SPEED 0.034
#define DHT_SENSOR_PIN 17
#define DHT_SENSOR_TYPE DHT11

// initialize DHT
DHT dht_sensor(DHT_SENSOR_PIN, DHT_SENSOR_TYPE);

// Tegangan dari MicroController, berfungsi untuk convert analog di sensor pH
const float vcc = 3.3;

// wifi autha
const char* ssid = "Artemis";
const char* password = "antihack22";

// url store data
const char* tinggiBakAirAPI = "http://34.101.128.49/api/store/data/tinggi_bak_air";
const char* tinggiNutrisiAAPI = "http://34.101.128.49/api/store/data/tinggi_nutrisi_a";
const char* tinggiNutrisiBAPI = "http://34.101.128.49/api/store/data/tinggi_nutrisi_b";
const char* tempAPI = "http://34.101.128.49/api/store/data/suhu";
const char* kelembabanAPI = "http://34.101.128.49/api/store/data/kelembaban";
const char* phAPI = "http://34.101.128.49/api/store/data/ph";

// url get data
const char* relayAAPI = "http://34.101.128.49/api/get/data/pompa_siram";
const char* relayBAPI = "http://34.101.128.49/api/get/data/pompa_nutrisi";
const char* relayCAPI = "http://34.101.128.49/api/get/data/pompa_mixer";

// delay per send data
unsigned long lastTime = 0;
unsigned long timerDelay = 5000;

// ultrasonic tinggi air
const int ultrasonic_air_trigger = 18;
const int ultrasonic_air_echo = 19;
// ultrasonic tinggi nutrisi A
const int ultrasonic_a_trigger = 18;
const int ultrasonic_a_echo = 19;
// ultrasonic tinggi nutrisi B
const int ultrasonic_b_trigger = 18;
const int ultrasonic_b_echo = 19;

long duration;
float distanceCm;

// PH
const int ph_pin = 34;
float PH4 = 3.55;
float PH7 = 3.19;

// Relay
const int relay_a_pin = 22;
const int relay_b_pin = 23;
const int relay_c_pin = 23;
void setup() {
  Serial.begin(115200);

  // trigger pin untuk ultrasonic 1
  pinMode(ultrasonic_air_trigger, OUTPUT);
  // echo pin untuk ultrasonic 1
  pinMode(ultrasonic_air_echo, INPUT);

  // trigger pin untuk ultrasonic a
  pinMode(ultrasonic_a_trigger, OUTPUT);
  // echo pin untuk ultrasonic a
  pinMode(ultrasonic_a_echo, INPUT);

  // trigger pin untuk ultrasonic b
  pinMode(ultrasonic_b_trigger, OUTPUT);
  // echo pin untuk ultrasonic b
  pinMode(ultrasonic_b_echo, INPUT);

  // ph
  // pinMode(ph_pin, INPUT);

  // relay
  pinMode(relay_a_pin, OUTPUT);

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
  // Membersihkan trigger
  digitalWrite(trigger, LOW);
  delayMicroseconds(2);
  // Menyetel pemicu pada status HIGH selama 10 mikro detik
  digitalWrite(trigger, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigger, LOW);

  // Membaca echo (gema), mengembalikan waktu tempuh gelombang suara dalam mikrodetik
  duration = pulseIn(echo, HIGH);

  // Menghitung jarak
  distanceCm = duration * SOUND_SPEED / 2;

  return distanceCm;
}

float temp() {
  // Membaca data temperature dari sensor dht
  float data = dht_sensor.readTemperature();

  // jika variable data tidak memiliki isi, maka sensor gagal (kemungkinan rusak atau tidak terpasang dengan benar)
  // dan akan langsung mengembalikan nilai nya sebesar -999 sebagai hasil yg gagal
  if (isnan(data)) {
    Serial.println("Gagal membaca data temperature dari DHT Sensor!");
    return -999;
  }
  // jika variable temp ada, maka
  return data;
}

float humi() {
  // Membaca data kelembaban dari sensor dht
  float data = dht_sensor.readHumidity();

  // jika variable data tidak memiliki isi, maka sensor gagal (kemungkinan rusak atau tidak terpasang dengan benar)
  // dan akan langsung mengembalikan nilai nya sebesar -999 sebagai hasil yg gagal
  if (isnan(data)) {
    Serial.println("Gagal membaca data kelembaban dari DHT Sensor!");
    return -999;
  }
  return data;
}

float sensor_ph() {
  // membaca nilai analog dari sensor pH
  int data_analog = analogRead(ph_pin);

  // menghitung tegangan (V) yang di hasilkan dari data analog
  double tegangan_ph = vcc / 1024.0 * data_analog;

  // menghitung jarak tegangan antar 1 kenaikan pH
  float ph_step = (PH4 - PH7) / 3;

  // menghitung pH
  float data = 7.00 + ((PH7 - tegangan_ph) / ph_step);
  return data;
}

void postData(String host, String data) {
  // melakukan pengecekan wifi
  if (WiFi.status() == WL_CONNECTED) {
    // Menginisialisasi WiFiClient, dan HTTPClient
    WiFiClient client;
    HTTPClient http;

    // deklarasi awal untuk kirim data
    http.begin(client, host);

    // content-type di set menjadi form
    // agar data bisa menggunakan format key1=value1&key2=value2
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    // http.addHeader("Content-Type", "application/json");
    // http.addHeader("Content-Type", "application/xml");

    // kirim data dengan metode POST
    int httpResponseCode = http.POST(data);

    // mengeluarkan nilai agar lebih mudah untuk debug, jika terjadi masalah
    Serial.println("----------------------------------------------------");
    Serial.println("URL API       : " + host);
    Serial.println("Data          : " + data);
    Serial.println("Kode Response : " + String(httpResponseCode));
    Serial.println("----------------------------------------------------");
    Serial.println();

    // untuk membersihkan resource, agar tidak menumpuk beban pekerjaannya
    http.end();
  } else {
    Serial.println("WiFi Disconnected");
  }
}

int getData(String host, int pin, int delay) {
  // melakukan pengecekan wifi
  if (WiFi.status() == WL_CONNECTED) {
    // Menginisialisasi WiFiClient, dan HTTPClient
    WiFiClient client;
    HTTPClient http;

    // deklarasi awal untuk kirim data
    http.begin(client, host);


    // kirim data dengan metode POST
    int httpResponseCode = http.GET(data);

    if (httpResponseCode == 200) {
      relay(pin, delay);
      postData(host, 'value=0');
    }
    // mengeluarkan nilai agar lebih mudah untuk debug, jika terjadi masalah
    Serial.println("----------------------------------------------------");
    Serial.println("URL API       : " + host);
    Serial.println("Data          : " + data);
    Serial.println("Kode Response : " + String(httpResponseCode));
    Serial.println("----------------------------------------------------");
    Serial.println();

    // untuk membersihkan resource, agar tidak menumpuk beban pekerjaannya
    http.end();
  } else {
    Serial.println("WiFi Disconnected");
  }
}

void relay(pin, time) {
  digitalWrite(pin, LOW);
  delay(500);
  digitalWrite(pin, HIGHT);
  delay(time);
}

void loop() {
  // kirim data per timerDelay
  if ((millis() - lastTime) > timerDelay) {

    // mengambil data ultrasonic tinggi air
    int ultrasonic_1 = ultrasonic(ultrasonic_air_trigger, ultrasonic_air_echo);
    // convert data int ke String ultrasonic tinggi air
    String data_ultrasonic_1 = "value=" + String(ultrasonic_1);
    // Mengirim data ultrasonic tinggi air ke website
    postData(tinggiBakAirAPI, data_ultrasonic_1);

    // mengambil dan mengirim data ultrasonic tinggi nutrisi A
    int ultrasonic_a = ultrasonic(ultrasonic_a_trigger, ultrasonic_a_echo);
    String data_ultrasonic_a = "value=" + String(ultrasonic_a);
    postData(tinggiNutrisiAAPI, data_ultrasonic_a);

    // mengambil dan mengirim data ultrasonic tinggi nutrisi B
    int ultrasonic_b = ultrasonic(ultrasonic_b_trigger, ultrasonic_b_echo);
    String data_ultrasonic_b = "value=" + String(ultrasonic_b);
    postData(tinggiNutrisiBAPI, data_ultrasonic_b);

    // mengambil dan mengirim data temperature
    float tempData = temp();
    String tempDataString = "value=" + String(tempData);
    postData(tempAPI, tempDataString);

    // mengambil dan mengirim data kelambaban
    float kelambabanData = humi();
    String kelambabanDataString = "value=" + String(kelambabanData);
    postData(kelembabanAPI, kelambabanDataString);

    // mengambil dan mengirim data ultrasoni
    float phData = sensor_ph();
    String phDataString = "value=" + String(phData);
    postData(phAPI, phDataString);

    getData(relayAAPI, relay_a_pin, 8000);
    getData(relayBAPI, relay_b_pin, 8000);
    getData(relayCAPI, relay_c_pin, 8000);

    // save waktu saat ini, untuk nanti di compare
    lastTime = millis();
    // 5000
  }
}