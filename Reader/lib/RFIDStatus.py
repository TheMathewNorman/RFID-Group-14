'''
' Author(s): Mathew Norman
' Date created: 10/04/18
' Description: 
'  Eases the process of displaying LED and Buzz status indicators for the RFID reading module.
'''

# Include libraries for functionality
from time import sleep
from gpiozero import Buzzer
import RPi.GPIO as GPIO

# Initialise GPIO in BCM mode without warnings
GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)

# Define pins for LEDs and Buzzer
redLED = 21
greenLED = 16
blueLED = 20

buzzer = 17

# Setup GPIO pins for the buzzer, status, fail, and success LEDs
GPIO.setup(buzzer, GPIO.OUT) # Buzzer
GPIO.setup(blueLED, GPIO.OUT) # Yellow
GPIO.setup(redLED, GPIO.OUT) # Red
GPIO.setup(greenLED, GPIO.OUT) # Green



class RFIDStatus:
	# Fail LED Methods
	def red_on(self):
		GPIO.output(redLED,GPIO.HIGH)
	
	def red_off(self):
		GPIO.output(redLED,GPIO.LOW)
	
	def red_slowflash(self):
		GPIO.output(redLED,GPIO.HIGH)
		sleep(1.0)
		GPIO.output(redLED,GPIO.LOW)
	
	def red_quickflash(self):
		GPIO.output(redLED,GPIO.HIGH)
		sleep(0.1)
		GPIO.output(redLED,GPIO.LOW)
		
	# Success LED Methods
	def green_on(self):
		GPIO.output(greenLED,GPIO.HIGH)
	
	def green_off(self):
		GPIO.output(greenLED,GPIO.LOW)
	
	def green_slowflash(self):
		GPIO.output(greenLED,GPIO.HIGH)
		sleep(1.0)
		GPIO.output(greenLED,GPIO.LOW)
	
	def green_quickflash(self):
		GPIO.output(greenLED,GPIO.HIGH)
		sleep(0.1)
		GPIO.output(greenLED,GPIO.LOW)
		
	# Status LED Methods
	def blue_on(self):
		GPIO.output(blueLED,GPIO.HIGH)
	
	def blue_off(self):
		GPIO.output(blueLED,GPIO.LOW)
	
	def blue_slowflash(self):
		GPIO.output(blueLED,GPIO.HIGH)
		sleep(1.0)
		GPIO.output(blueLED,GPIO.LOW)
	
	def blue_quickflash(self):
		GPIO.output(blueLED,GPIO.HIGH)
		sleep(0.1)
		GPIO.output(blueLED,GPIO.LOW)
		
	# Status Buzzer Methods
	def buzzer_on(self):
		GPIO.output(buzzer,GPIO.HIGH)
	
	def buzzer_off(self):
		GPIO.output(buzzer,GPIO.LOW)
	
	def slow_buzz(self):
		GPIO.output(buzzer,GPIO.HIGH)
		sleep(1.0)
		GPIO.output(buzzer,GPIO.LOW)
	
	def quick_buzz(self):
		GPIO.output(buzzer,GPIO.HIGH)
		sleep(0.1)
		GPIO.output(buzzer,GPIO.LOW)
		
	