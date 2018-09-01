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
#from lib import RFIDStatus # Import custom library for RFID status.
#from lib import Validate # Import custom library for user validation library.

reader = SimpleMFRC522.SimpleMFRC522()
#status = RFIDStatus.RFIDStatus()
#validate = Validate.Validate()

# def accessGranted():
# 	status.green_on()
# 	status.quick_buzz()
# 	sleep(10)
# 	status.green_off()
	
	
# def accessDenied():
# 	status.red_on()
# 	status.quick_buzz()
# 	status.quick_buzz()
# 	status.quick_buzz()
# 	sleep(10)
# 	status.red_off()
	

# Read card reader input
while True:
    print("Ready to read...")
    id, time = reader.read()
    print(id);
	