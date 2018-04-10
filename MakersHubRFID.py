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

id, time = reader.read()
status.quick_buzz()
print(id)
raw_input("Press any key to exit.")

GPIO.cleanup()