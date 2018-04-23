# Include libraries for functionality
from time import sleep
from gpiozero import Buzzer
import RPi.GPIO as GPIO

# Initialise GPIO in BCM mode without warnings
GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)

# Define pins for LEDs and Buzzer
redLED = 2
greenLED = 3
blueLED = 4

buzzer = 21

GPIO.setup(buzzer, GPIO.OUT) # Buzzer
GPIO.setup(blueLED, GPIO.OUT) # Yellow
GPIO.setup(redLED, GPIO.OUT) # Red
GPIO.setup(greenLED, GPIO.OUT) # Green

print "Red"
GPIO.output(redLED,GPIO.HIGH)
sleep(1.0)
GPIO.output(redLED,GPIO.LOW)
sleep(1.0)

print "Green"
GPIO.output(greenLED,GPIO.HIGH)
sleep(1.0)
GPIO.output(greenLED,GPIO.LOW)
sleep(1.0)

print "Blue"
GPIO.output(blueLED,GPIO.HIGH)
sleep(1.0)
GPIO.output(blueLED,GPIO.LOW)
sleep(1.0)

print "Buzzer"
GPIO.output(buzzer,GPIO.HIGH)
sleep(1.0)
GPIO.output(buzzer,GPIO.LOW)
sleep(1.0)

print "That's it"
