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

print("Quick buzz in 2")
sleep(2)
status.quick_buzz()
print("Slow buzz in 2")
sleep(2)
status.slow_buzz()
sleep(2)
