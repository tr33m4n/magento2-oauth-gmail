# magento2-oauth-gmail
A simple way to send mail via the Gmail API with Oauth authentication

## This is alpha software!
This module allows Magento to send emails through the Gmail API using Oauth authentication, instead of SMTP.

## TODO
- [ ] Allow send failure to fallback to default
- [ ] Allow configuring different test email addresses
- [ ] Better handling of refresh token errors
- [ ] Add instructions/wiki for configuring the Oauth application within the Google Developer console https://developers.google.com/workspace/guides/create-credentials
- [ ] Add unit/integration tests
- [ ] Add CI

## Installing
This module is available on https://packagist.org/
```sh
composer require tr33m4n/magento2-oauth-gmail
