#!/usr/bin/env python

'''
' Author(s): Mathew Norman
' Date created: 10/04/18
' Description: 
'  Simulates the process of reading and validating RFID cards on the Raspberry Pi for the RFID Readers.
'''

# Import libraries.
from gpiozero import Buzzer # Import library for buzzer.
from time import sleep # Import library for sleep.
import RPi.GPIO as GPIO # Import library for GPIO pins.

# Import local libraries.
from lib import SimpleMFRC522 # Import library for MFRC522.
from lib import RFIDStatus # Import Mat's custom library for RFID status.
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
	status.green_on()
	status.quick_buzz()
	sleep(10)
	status.green_off()
	
	
def accessDenied():
	status.red_on()
	status.quick_buzz()
	status.quick_buzz()
	status.quick_buzz()
	sleep(10)
	status.red_off()
	

# Read card reader input
while True:
	print("Ready to read...")
	status.blue_on()
	id, time = reader.read()
	status.blue_off()
	if (validate(id)):
		accessGranted()
	else:
		accessDenied()
	