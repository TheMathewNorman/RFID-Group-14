#!/usr/bin/env python

# Import libraries.
from gpiozero import Buzzer # Import library for buzzer.
from time import sleep # Import library for sleep.
import RPi.GPIO as GPIO # Import library for GPIO pins.

# Import local libraries.
from lib import SimpleMFRC522 # Import library for MFRC522.
from lib import MatsRFIDStatus # Import Mat's custom library for RFID status.
from lib import ValidateUser # Import user validation library.

reader = SimpleMFRC522.SimpleMFRC522()
status = MatsRFIDStatus.MatsRFIDStatus()
# valudateuser = ValidateUser.ValidateUser()

validUsers = [17988527649, 595116637326, 252747632322]
def validate(id):
	if id in validUsers:
		return True
	else:
		return False

def accessGranted():
	status.success_on()
	status.quick_buzz()
	sleep(10)
	status.success_off()
	
	
def accessDenied():
	status.fail_on()
	status.quick_buzz()
	status.quick_buzz()
	status.quick_buzz()
	sleep(10)
	status.fail_off()
	

# Read card reader input
while True:
	print("Ready to read...")
	status.status_on()
	id, time = reader.read()
	status.status_off()
	if (validate(id)):
		accessGranted()
	else:
		accessDenied()
	