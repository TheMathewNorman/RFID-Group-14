# Requires requests (>pip install requests)
import requests

# Example verification of card "987654321" by reader "12345"
r = reponse.get("therfid.men/reader/verify.request.php?readerid=12345&cardid=987654321");

if r.status_code == 200: # r.status_code = 200 on success.
    # Unlock
else # r.status_code = 403 on failure.
    # Keep locked
