# Example list of valid card IDs
validUsers = [17988527649, 595116637326, 252747632322]

class ValidateUser:
	
	def validateCard(self, id):
		if id in validUsers:
			return True
		else:
			return False