#!/usr/bin/env python

# Import libraries
from gpiozero import Buzzer # Import library for buzzer
from time import sleep # Import library for sleep
import RPi.GPIO as GPIO # Import library for GPIO pins

# Import local libraries
from lib import SimpleMFRC522 # Import library for MFRC522
from lib import MatsRFIDStatus # Import Mat's custom library for RFID status.

reader = SimpleMFRC522.SimpleMFRC522()
status = MatsRFIDStatus.MatsRFIDStatus()

userexit = ""

while true:
	print("Ready to read...")
	status.status_on()
	id, time = reader.read()
	status.status_off()
	status.success_on()
	status.quick_buzz()
	status.success_off()
	print(id)

GPIO.cleanup()