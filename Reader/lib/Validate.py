'''
' Author(s): Mathew Norman
' Date created: 10/04/18
' Description: 
'  Simulates a card validation
'''

class Validate:

    # Example list of valid card IDs
	validCards = [17988527649, 595116637326, 252747632322]

	def card(self, id):
		if id in validCards:
			return True
		else:
			return False