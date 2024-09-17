#include "SoftwareSerial.h"

// SoftwareSerial megaSerial(13,12);

// RFID 3.3v, MOSI = 51, MISO = 50, SCK = 52, RST = 49, SDA = 53
// Servo 5v brown-gnd, redc 5v, orange pin
// LCD vcc 5v, sda 20, scl 21
#include <Servo.h> //Servo Module
unsigned long EnMillis = 0;
unsigned long ExMillis = 0;
unsigned long FullPark = 0;
unsigned long Scanned = 0;
unsigned long carUpdate = 0;
unsigned long lcdUpdate = 0;
bool lcdTick = true;
const long interval = 2000;

#include <MFRC522.h> //RFID Module 
#include "SPI.h"
#include <Wire.h> 
#include <LiquidCrystal_I2C.h> //LCD Module
LiquidCrystal_I2C lcd(0x27, 16, 2);

#define RX_PIN 12 //10 dati
#define TX_PIN 13 // 11 dati
#define BAUD_RATE 9600
#define BAUD_RATE_ESP 115200
#define RST_PIN         49         // Configurable, see typical pin layout above
#define SS_PIN          53         // Configurable, see typical pin layout above //SDA


String tmpUID = "";
MFRC522 mfrc522(SS_PIN, RST_PIN);  // Create MFRC522 instance

Servo entrance;
Servo exitServo;

String carSpace = "0000";
String carSpacePrev = "0000";
String carSpacePrevExit = "0000";
int carSpaceCounter = 3;
int slotNum = 0;
String tmpCarSpaceCounter = "";


void setup() {
   // initialize serial for debugging
  Serial.begin(BAUD_RATE);
  Serial1.begin(BAUD_RATE_ESP);
  // initialize ESP module
  delay(1000);
  SPI.begin();
	mfrc522.PCD_Init();		// Init MFRC522
	delay(4);				// Optional delay. Some board do need more time after init to be ready, see Readme
  entrance.attach(9);
  exitServo.attach(10);
  entrance.write(0);
  exitServo.write(0);  
  lcd.begin();
  lcdDisplay("Welcome to", "Smart Parking!");
  // EXIT 38 TRIG,39 ECHO
  // CAR 1 TO 3 EVEN TRIG, ODD ECHO, 40 TO 45
  // newUpdate exit 40 trig, 41 echo
  for (int start = 34; start<=41; start+=1) {
    if(start%2!=0){
      pinMode(start, INPUT);
    }
    else{
      pinMode(start, OUTPUT);
    }
  }
  pinMode(12,OUTPUT);
}

void loop() {
 
  tmpCarSpaceCounter = String(3-carSpaceCounter);
  ultraSonic();
  // delay(1000);
  RFID();
  exitGate();
  // delay(1000);
}

void beeper(){
    digitalWrite(12, HIGH);
    delay(50);
    digitalWrite(12, LOW);    
    delay(50);
    digitalWrite(12, HIGH);
    delay(50);
    digitalWrite(12, LOW);  
}
String readData(){
  String data = Serial1.readStringUntil('\n');
  return data;
}

void ultraSonic(){

  char exit = ultraCompute(40,41);
  char car1 = ultraCompute(34,35);
  char car2 = ultraCompute(36,37);
  char car3 = ultraCompute(38,39);

  carSpace[0] = exit;
  carSpace[1] = car1;
  carSpace[2] = car2;
  carSpace[3] = car3;
  carSpaceCounter = ((int(carSpace[1]-48))+(int(carSpace[2]-48))+(int(carSpace[3]-48)));
  if(carSpace[1]=='0'){
    slotNum = 1;
  }
  else if(carSpace[2]=='0'){
    slotNum = 2;
  }
  else if(carSpace[3]=='0'){
    slotNum = 3;
  }
  else{
    slotNum = 0;
  }
  if (millis() - carUpdate >= interval && carUpdate != 0) {
    carUpdate = 0;
  }

  if(carSpace[1]!=carSpacePrev[1] || carSpace[2]!=carSpacePrev[2] || carSpace[3]!=carSpacePrev[3] && carUpdate == 0){
    carSpacePrev = carSpace;
    String carSend = "^" + String(slotNum) + String(carSpace[1]) + String(carSpace[2]) + String(carSpace[3]);
    Serial1.println(carSend);
    carUpdate = millis();
  }

}

char ultraCompute(int trig,int echo){
  long duration, distance;
  digitalWrite(trig, LOW);
  // delayMicroseconds(2);
  digitalWrite(trig, HIGH);
  // delayMicroseconds(10);
  digitalWrite(trig, LOW);
  duration =  pulseIn(echo, HIGH);
  distance =  (duration/2)/29.1;
  if(trig==32){
    Serial.println(distance);
  }
  char returnDistance = (distance <= 5 ? '1' : '0');

  return returnDistance;
}

void exitGate(){
  if (millis() - ExMillis >= interval && ExMillis != 0 && exitServo.read() == 90) { // Check if 2000 milliseconds have passed since the gate opened
    Servos("exitClose");
    ExMillis = 0;
  }
  
  if(carSpace[0]== '1'){   
      Servos("exitOpen");
      ExMillis = millis();
      Serial.println("exit");
      Serial.println(carSpacePrevExit);
      Serial.println(carSpace);

      if(carSpace[1]!=carSpacePrevExit[1] || carSpace[2]!=carSpacePrevExit[2] || carSpace[3]!=carSpacePrevExit[3]){
        String timeOut = "!";
        beeper();
        if(carSpace[1]!=carSpacePrevExit[1]){
          timeOut += "1";
        }
        else if(carSpace[2]!=carSpacePrevExit[2]){
          timeOut += "2";
        }
        else if(carSpace[3]!=carSpacePrevExit[3]){
          timeOut += "3";
        }
        Serial1.println(timeOut);
      }
      carSpacePrevExit = carSpace;        
  }
}
void RFID(){  
 
  if(millis() - lcdUpdate >= 5000 && lcdUpdate !=0){
    lcdUpdate = 0;
    if(lcdTick){
      lcdDisplay("Space available: ", tmpCarSpaceCounter);
      lcdTick = false;
      lcdUpdate = millis();
    }
    else{
      lcdDisplay("Welcome to", "Smart Parking!");
      lcdTick = true;
      lcdUpdate = millis();
    }
  }
  if(millis() - Scanned >= interval && Scanned !=0){
    Scanned = 0;
  }
  // Reset the loop if no new card present on the sensor/reader. This saves the entire process when idle.
	if ( ! mfrc522.PICC_IsNewCardPresent()) {
    if(millis() - FullPark >= interval && FullPark !=0){
      lcd.clear();
      lcdDisplay("Welcome to", "Smart Parking!");
      FullPark = 0;    
    }
    if (millis() - EnMillis >= interval && EnMillis != 0 && entrance.read() == 90) { // Check if 2000 milliseconds have passed since the gate opened
      Servos("entranceClose"); 
      lcd.clear();  
      lcdDisplay("Welcome to", "Smart Parking!");
      EnMillis = 0;
      tmpUID = "";
    }
    return;
  }

  if ( ! mfrc522.PICC_ReadCardSerial()) {
		return;
	}

  Serial.println(carSpace);

  if(carSpaceCounter == 3){
    lcdDisplay("Full", "Parking!");
    FullPark = millis();
    return;
  }
  else{
    
    if(Scanned==0){
      String uID = formatUID();
      Serial.println("uid:"+uID);  
      Serial1.println(uID); 
      tmpUID = readData();
      Serial.println("tuid: "+tmpUID);
      Scanned = millis();
      beeper();
    }
    if(tmpUID.startsWith("#") ){
        String name = tmpUID;
        name.remove(0,1);
        name.remove(name.length()-1,1);
        lcdDisplay("Welcome", name);
        Servos("entranceOpen");        
        EnMillis = millis();
        carSpacePrevExit = carSpace;
        carSpacePrevExit[slotNum] = '1';
        Serial.println(carSpacePrevExit);
    }
    
  }
  
}
String formatUID(){
  String uID = "#"+ String(slotNum);
  for(byte i=0;i< mfrc522.uid.size;i++)
  {
    uID.concat(String(mfrc522.uid.uidByte[i] < 0x10 ? "0": ""));
    uID.concat(String(mfrc522.uid.uidByte[i], HEX));
  }  
  return uID;
}

void lcdDisplay(String up, String down){
  // Turn on the blacklight and print a message.
  if(up != "Welcome to" && down != "Smart Parking!"){    
    lcd.clear();
  }
  else if(up == "Welcome to" && down == "Smart Parking!"){
    lcd.clear();
    lcdUpdate = millis();
  }
  int cursorUp = (16-up.length())/2;
  int cursorDown = (16-down.length())/2;

  lcd.setCursor(cursorUp,0);
	lcd.print(up);
  lcd.setCursor(cursorDown,1);
	lcd.print(down);
  
  
}

void Servos(String data){
  if(data == "entranceOpen"){
    entrance.write(90);
  }else if(data == "entranceClose"){
    entrance.write(0);
  }
  
  if(data == "exitOpen"){
    exitServo.write(90);
  }else if (data == "exitClose"){
    exitServo.write(0);
  }
}
