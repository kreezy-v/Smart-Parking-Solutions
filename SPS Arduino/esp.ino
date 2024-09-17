#include "HardwareSerial.h"
#include <WiFi.h>
#include <HTTPClient.h>
#include <WebServer.h>

HardwareSerial espSerial(2);

WebServer server(80);

const char* ssid = "esp101";
const char* password = "12345678";

String serverName = "http://192.168.1.126/sps";

void setup() {
  // put your setup code here, to run once:
  Serial.begin(9600);
  espSerial.begin(115200, SERIAL_8N1, 16, 17);
  connect();
  beginServer();
  
}
void loop() {
  if(espSerial.available()){
    String a = espSerial.readStringUntil('\n'); 
    Serial.println(a);
    if(a.startsWith("#")){
      a.remove(0,1);
      espSerial.println(getRFID(a));
    }
    else if(a.startsWith("!")){
      a.remove(0,1);
      timeOut(a);
    }
    else if(a.startsWith("^")){
      a.remove(0,1);
      carSpace(a);
    }
  }
  server.handleClient();
  server.send(200, "text/html", "<form action='/' method='post'> <button type='submit' name='hi'></button> </form>");
  
}

void connect(){
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
}

String getRFID(String rfid){
  
  String slotNum = String(rfid[0]);
  rfid.remove(0,1);
  String payload;
  if(WiFi.status()== WL_CONNECTED){
      String data = "rfidText=csu" + rfid;
      HTTPClient http;
      String serverPath = serverName + "/rfid.php";
      Serial.println(serverPath);
      http.begin(serverPath);
      // Specify content-type header
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");
      
       // Send HTTP GET request
      int httpResponseCode = http.POST(data);
      
      if (httpResponseCode>0) {
        Serial.print("HTTP Response code: ");
        Serial.println(httpResponseCode);
        payload = http.getString();
        Serial.println(payload);
      }
      else {
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
      }      
      http.end();

      if(payload.startsWith("#")){      
        data = "id=" + String(payload[1]) + "&slotNum=" + slotNum;
        serverPath = serverName + "/upUserSlot.php";
        Serial.println(serverPath);
        http.begin(serverPath);
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");
        
        // Send HTTP GET request
        httpResponseCode = http.POST(data);
        
        if (httpResponseCode>0) {
          Serial.print("HTTP Response code: ");
          Serial.println(httpResponseCode);
          String updateSlot = http.getString();
          Serial.println(updateSlot);
        }
        else {
          Serial.print("Error code: ");
          Serial.println(httpResponseCode);
        }      
        http.end();
      }

  }
  else {
    Serial.println("WiFi Disconnected");
  }
  
  payload.remove(1,1);
  return payload;
}

void timeOut(String tOut){
if(WiFi.status()== WL_CONNECTED){
      String data = "slot=" + tOut;
      HTTPClient http;
      String serverPath = serverName + "/upExit.php";
      Serial.println(serverPath);
      http.begin(serverPath);
      // Specify content-type header
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");
      
       // Send HTTP GET request
      int httpResponseCode = http.POST(data);
      
      if (httpResponseCode>0) {
        Serial.print("HTTP Response code: ");
        Serial.println(httpResponseCode);
        String payload = http.getString();
        Serial.println(payload);
      }
      else {
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
      }      
      http.end();
  }
  else {
    Serial.println("WiFi Disconnected");
  }
}

void carSpace(String cars){
  if(WiFi.status()== WL_CONNECTED){
      String data = "cars=" + cars;
      HTTPClient http;
      String serverPath = serverName + "/upCars.php";
      Serial.println(serverPath);
      http.begin(serverPath);
      // Specify content-type header
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");
      
       // Send HTTP GET request
      int httpResponseCode = http.POST(data);
      
      if (httpResponseCode>0) {
        Serial.print("HTTP Response code: ");
        Serial.println(httpResponseCode);
        String payload = http.getString();
        Serial.println(payload);
      }
      else {
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
      }      
      http.end();
  }
  else {
    Serial.println("WiFi Disconnected");
  }
}

void beginServer(){

  server.on("/",handleRoot);
  server.begin();
  Serial.println("HTTP SERVER STARTED");
  
}

void handleRoot(){
  
  if(server.hasArg("hi")){
    Serial.println("received hi");
  }
}