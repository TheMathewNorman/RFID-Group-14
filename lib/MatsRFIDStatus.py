# Include libraries for functionality
from time import sleep
from gpiozero import Buzzer
import RPi.GPIO as GPIO

#configure fail led
#configure success led
#configure status led
#configure buzzer

GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)

GPIO.setup(16, GPIO.OUT) # Yellow
GPIO.setup(20, GPIO.OUT) # Red
GPIO.setup(21, GPIO.OUT) # Green

class MatsRFIDStatus:
	# Fail LED Methods
	def fail_on(self):
		GPIO.output(20,GPIO.HIGH)
	
	def fail_off(self):
		GPIO.output(20,GPIO.LOW)
	
	def fail_slowflash(self):
		GPIO.output(20,GPIO.HIGH)
		sleep(1.5)
		GPIO.output(20,GPIO.LOW)
	
	def fail_quickflash(self):
		GPIO.output(20,GPIO.HIGH)
		sleep(0.5)
		GPIO.output(20,GPIO.LOW)
		
	# Success LED Methods
	def success_on(self):
		GPIO.output(21,GPIO.HIGH)
	
	def success_off(self):
		GPIO.output(21,GPIO.LOW)
	
	def success_slowflash(self):
		GPIO.output(21,GPIO.HIGH)
		sleep(1.5)
		GPIO.output(21,GPIO.LOW)
	
	def success_quickflash(self):
		GPIO.output(21,GPIO.HIGH)
		sleep(0.5)
		GPIO.output(21,GPIO.LOW)
		
	# Status LED Methods
	def status_on(self):
		GPIO.output(16,GPIO.HIGH)
	
	def status_off(self):
		GPIO.output(16,GPIO.LOW)
	
	def status_slowflash(self):
		GPIO.output(16,GPIO.HIGH)
		sleep(1.5)
		GPIO.output(16,GPIO.LOW)
	
	def status_quickflash(self):
		GPIO.output(16,GPIO.HIGH)
		sleep(0.5)
		GPIO.output(16,GPIO.LOW)
		
	# Status Buzzer Methods
	def buzzer_on(self):
		GPIO.output(4,GPIO.HIGH)
	
	def buzzer_off(self):
		GPIO.output(4,GPIO.LOW)
	
	def slow_buzz(self):
		GPIO.output(4,GPIO.HIGH)
		sleep(1.5)
		GPIO.output(4,GPIO.LOW)
	
	def quick_buzz(self):
		GPIO.output(4,GPIO.HIGH)
		sleep(0.5)
		GPIO.output(4,GPIO.LOW)
		
	