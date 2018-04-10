# Include libraries for functionality
from time import sleep
from gpiozero import Buzzer
import RPi.GPIO as GPIO

# Initialise GPIO in BCM mode without warnings
GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)

# Define pins for LEDs and Buzzer
redLED = 20
yellowLED = 16
greenLED = 21
buzzer = 4

# Setup GPIO pins for the buzzer, status, fail, and success LEDs
GPIO.setup(buzzer, GPIO.OUT) # Buzzer
GPIO.setup(yellowLED, GPIO.OUT) # Yellow
GPIO.setup(redLED, GPIO.OUT) # Red
GPIO.setup(greenLED, GPIO.OUT) # Green



class MatsRFIDStatus:
	# Fail LED Methods
	def fail_on(self):
		GPIO.output(redLED,GPIO.HIGH)
	
	def fail_off(self):
		GPIO.output(redLED,GPIO.LOW)
	
	def fail_slowflash(self):
		GPIO.output(redLED,GPIO.HIGH)
		sleep(1.0)
		GPIO.output(redLED,GPIO.LOW)
	
	def fail_quickflash(self):
		GPIO.output(redLED,GPIO.HIGH)
		sleep(0.1)
		GPIO.output(redLED,GPIO.LOW)
		
	# Success LED Methods
	def success_on(self):
		GPIO.output(greenLED,GPIO.HIGH)
	
	def success_off(self):
		GPIO.output(greenLED,GPIO.LOW)
	
	def success_slowflash(self):
		GPIO.output(greenLED,GPIO.HIGH)
		sleep(1.0)
		GPIO.output(greenLED,GPIO.LOW)
	
	def success_quickflash(self):
		GPIO.output(greenLED,GPIO.HIGH)
		sleep(0.1)
		GPIO.output(greenLED,GPIO.LOW)
		
	# Status LED Methods
	def status_on(self):
		GPIO.output(yellowLED,GPIO.HIGH)
	
	def status_off(self):
		GPIO.output(yellowLED,GPIO.LOW)
	
	def status_slowflash(self):
		GPIO.output(yellowLED,GPIO.HIGH)
		sleep(1.0)
		GPIO.output(yellowLED,GPIO.LOW)
	
	def status_quickflash(self):
		GPIO.output(yellowLED,GPIO.HIGH)
		sleep(0.1)
		GPIO.output(yellowLED,GPIO.LOW)
		
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
		
	