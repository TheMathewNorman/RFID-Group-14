#!/usr/bin/env python

# Import libraries
from gpiozero import Buzzer # Import library for buzzer
from time import sleep # Import library for sleep
import RPi.GPIO as GPIO # Import library for GPIO pins

# Import local libraries
from lib import SimpleMFRC522 # Import library for MFRC522

reader = SimpleMFRC522.SimpleMFRC522()

try:
	id, text = reader.read()
	print(id)
finally:
	GPIO.cleanup()