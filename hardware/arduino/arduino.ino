int vcc = 5;

float PH4 = 3.67;
float PH7 = 3.34;

void setup() {
  Serial.begin(115200);
}

void loop() {

  // membaca sensor ph menjadi sinyal analog 
  int data_analog = analogRead(A0);
  // convert data_analog ke tegangan (voltage)
  double tegangan_ph = vcc / 1024.0 * data_analog;
  // menghitung step (jarak tegangan antar ph) 
  float ph_step = (PH4 - PH7) / 3;
  // menghitung ph
  float data = 7.00 + ((PH7 - tegangan_ph) / ph_step);
  // menampilkan, sekaligus mengirim data ke esp
  Serial.println(data);
  
  delay(1000);

}
