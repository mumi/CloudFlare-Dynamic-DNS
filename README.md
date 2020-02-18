# CloudFlare-Dynamic-DNS
A short cURL based PHP script with an easy API to change CloudFlare Records to use it as DDNS service.

# API:
example.com/cf-ddns.php?domain=**DOMAIN**&token=**CLOUDFLARE-TOKEN**&ip=**IP ADRESS**


- IPv4 or IPv6 (prefers IPv4, no DualStack)
- This type of API is supported by most routers etc.
- Subdomain (e.g. home.chicken.com) or normal domain? If you want to use a normal Domain (e.g. chicken.com) edit the variable in the php file:
```php
$use_subdomain = true;
```
to
```php
$use_subdomain = false;
```
