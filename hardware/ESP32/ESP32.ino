#include <WiFi.h>
#include <HTTPClient.h>
#include <DHT.h>

#define SOUND_SPEED 0.034
#define PIN_DHT_SENSOR 21
#define TYPE_DHT_SENSOR DHT11

// initialize DHT
DHT dht_sensor(PIN_DHT_SENSOR, TYPE_DHT_SENSOR);

// ini pin di esp32, yang akan menjalankan fungsi komunikasi ke arduino
#define RX 16
#define TX 17

// wifi auth
const char* ssid = "RyndamHost";
const char* password = "Ryndam343";

// url store data
const char* api_tinggi_bak_air = "http://34.101.128.49/api/store/data/tinggi_bak_air";
const char* api_tinggi_nutrisi_a = "http://34.101.128.49/api/store/data/tinggi_nutrisi_a";
const char* api_tinggi_nutrisi_b = "http://34.101.128.49/api/store/data/tinggi_nutrisi_b";
const char* api_temperature = "http://34.101.128.49/api/store/data/suhu";
const char* api_kelembaban = "http://34.101.128.49/api/store/data/kelembaban";
const char* api_ph = "http://34.101.128.49/api/store/data/ph";
const char* api_relay_siram = "http://34.101.128.49/api/store/data/pompa_siram";
const char* api_relay_nutrisi = "http://34.101.128.49/api/store/data/pompa_nutrisi";
const char* api_relay_mixer = "http://34.101.128.49/api/store/data/pompa_mixer";

// url get data
const char* api_get_relay_siram = "http://34.101.128.49/api/get/data/pompa_siram";
const char* api_get_relay_nutrisi = "http://34.101.128.49/api/get/data/pompa_nutrisi";
const char* api_get_relay_mixer = "http://34.101.128.49/api/get/data/pompa_mixer";

// delay per send data
unsigned long lastTime = 0;
unsigned long timerDelay = 5000;

unsigned long lastTimePompa = 0;
unsigned long timerDelayPompa = 600000;

// ultrasonic tinggi air
const int pin_ultrasonic_air_trigger = 18;
const int pin_ultrasonic_air_echo = 19;
// ultrasonic tinggi nutrisi A
const int pin_ultrasonic_a_trigger = 22;
const int pin_ultrasonic_a_echo = 23;
// ultrasonic tinggi nutrisi B
const int pin_ultrasonic_b_trigger = 12;
const int pin_ultrasonic_b_echo = 13;

long duration;
float distanceCm;

// Relay
const int pin_relay_siram = 32;
const int pin_relay_nutrisi = 33;
const int pin_relay_mixer = 25;

void setup() {
  Serial.begin(115200);

  // trigger pin untuk ultrasonic 1
  pinMode(pin_ultrasonic_air_trigger, OUTPUT);
  // echo pin untuk ultrasonic 1
  pinMode(pin_ultrasonic_air_echo, INPUT);

  // trigger pin untuk ultrasonic a
  pinMode(pin_ultrasonic_a_trigger, OUTPUT);
  // echo pin untuk ultrasonic a
  pinMode(pin_ultrasonic_a_echo, INPUT);

  // trigger pin untuk ultrasonic b
  pinMode(pin_ultrasonic_b_trigger, OUTPUT);
  // echo pin untuk ultrasonic b
  pinMode(pin_ultrasonic_b_echo, INPUT);


  // relay
  pinMode(pin_relay_siram, OUTPUT);
  pinMode(pin_relay_nutrisi, OUTPUT);
  pinMode(pin_relay_mixer, OUTPUT);

  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.println("Connecting...");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  Serial.println("Timer set to 5 seconds (timerDelay variable), it will take 5 seconds before publishing the first reading.");

  dht_sensor.begin();

  // mematikan semua relay
  digitalWrite(pin_relay_siram, HIGH);
  digitalWrite(pin_relay_nutrisi, HIGH);
  digitalWrite(pin_relay_mixer, HIGH);


  // Untuk membaca data dari arduino
  Serial2.begin(115200, SERIAL_8N1, RX, TX);
}

int ultrasonic(int trigger, int echo) {
  // Membersihkan/mematikan trigger
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

int getData(String host, int pin, int delay, String host_post) {
  // melakukan pengecekan wifi
  if (WiFi.status() == WL_CONNECTED) {
    // Menginisialisasi WiFiClient, dan HTTPClient
    WiFiClient client;
    HTTPClient http;

    // deklarasi awal untuk kirim data
    http.begin(client, host);


    // kirim data dengan metode GET
    int httpResponseCode = http.GET();


    Serial.println("----------------------------------------------------");
    Serial.println("URL API       : GET : " + host);
    Serial.println("Kode Response : " + String(httpResponseCode));
    Serial.println("----------------------------------------------------");
    Serial.println();

    if (httpResponseCode == 200) {
      relay(pin, delay);
      postData(host_post, "value=0");
    }
    // mengeluarkan nilai agar lebih mudah untuk debug, jika terjadi masalah
    Serial.println("----------------------------------------------------");
    Serial.println("URL API       : " + host);
    // Serial.println("Data          : " + data);
    Serial.println("Kode Response : " + String(httpResponseCode));
    Serial.println("----------------------------------------------------");
    Serial.println();

    // untuk membersihkan resource, agar tidak menumpuk beban pekerjaannya
    http.end();
  } else {
    Serial.println("WiFi Disconnected");
  }
}

void relay(int pin, int time) {
  digitalWrite(pin, HIGH);
  delay(500);
  digitalWrite(pin, LOW);
  delay(time);
  digitalWrite(pin, HIGH);
}

void loop() {
  // kirim data per timerDelay
  if ((millis() - lastTime) > timerDelay) {

    // mengambil data ultrasonic tinggi air
    int ultrasonic_1 = ultrasonic(pin_ultrasonic_air_trigger, pin_ultrasonic_air_echo);
    // convert data int ke String ultrasonic tinggi air
    String data_ultrasonic_1 = "value=" + String(ultrasonic_1);
    // Mengirim data ultrasonic tinggi air ke website
    postData(api_tinggi_bak_air, data_ultrasonic_1);

    // mengambil dan mengirim data ultrasonic tinggi nutrisi A
    int ultrasonic_a = ultrasonic(pin_ultrasonic_a_trigger, pin_ultrasonic_a_echo);
    String data_ultrasonic_a = "value=" + String(ultrasonic_a);
    postData(api_tinggi_nutrisi_a, data_ultrasonic_a);

    // mengambil dan mengirim data ultrasonic tinggi nutrisi B
    int ultrasonic_b = ultrasonic(pin_ultrasonic_b_trigger, pin_ultrasonic_b_echo);
    String data_ultrasonic_b = "value=" + String(ultrasonic_b);
    postData(api_tinggi_nutrisi_b, data_ultrasonic_b);

    // mengambil dan mengirim data temperature
    float tempData = temp();
    String tempDataString = "value=" + String(tempData);
    postData(api_temperature, tempDataString);

    // mengambil dan mengirim data kelambaban
    float kelambabanData = humi();
    String kelambabanDataString = "value=" + String(kelambabanData);
    postData(api_kelembaban, kelambabanDataString);

    // mengambil dan mengirim data ultrasoni
    float phData = Serial2.readString().toFloat();
    String phDataString = "value=" + String(phData);
    postData(api_ph, phDataString);


    // Get data untuk mengaktifkan pompa
    getData(api_get_relay_nutrisi, pin_relay_nutrisi, 10000, api_relay_nutrisi);
    getData(api_get_relay_mixer, pin_relay_mixer, 10000, api_relay_mixer);
    getData(api_get_relay_siram, pin_relay_siram, 10000, api_relay_siram);

    // save waktu saat ini, untuk nanti di compare
    lastTime = millis();
    // 5000
  }
  if ((millis() - lastTimePompa) > timerDelayPompa) {
    relay(pin_relay_nutrisi, 10000);
    relay(pin_relay_mixer, 10000);
    relay(pin_relay_siram, 10000);

    lastTimePompa = millis();
  }
}