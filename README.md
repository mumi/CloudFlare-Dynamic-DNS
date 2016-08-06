# CloudFlare-Dynamic-DNS
A short cURL based PHP script with an easy API to change CloudFlare Records to use it as DDNS service.

# API:
example.com/cf-ddns.php?domain=**DOMAIN**&email=**CLOUDFLARE-MAIL**&key=**CLOUDFLARE-API-KEY**&ip=**IP ADRESS**

e.g.:
```
https://damn.com/cf-ddns.php?domain=vpn.test.com&email=test@test.com&key=fe5a7b8ds6d76s67h4184c41&ip=23.45.86.54
```
- IPv4 or IPv6 (prefers IPv4, no DualStack)
- This type of API is supported by the most routers etc.
- The domain must be a subdomain (e.g. home.chicken.com . If you want to use the normal Domain (e.g. chicken.com) delete the first lines in the script.

e.g. FRITZ!Box:
![FRITZBox settings](http://i.imgur.com/wfL4yKU.jpg)

**In this case, username means Cloudflare Email adress and Password means Cloudflare-API-Key.**
**ipaddr is a variable by Fritzbox for the IP adress**
