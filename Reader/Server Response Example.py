# Requires requests (>pip install requests)
import requests

# Example verification of card "987654321" by reader "12345"
r = requests.get("http://therfid.men/php/reader/authenticate.php?key=662766669539&reader=1")

print(r.test)
