# PHP Client for SmsManager.cz

[![Build Status](https://travis-ci.org/danaketh/SMSManager.svg)](https://travis-ci.org/danaketh/SMSManager)
[![Quality Score](https://scrutinizer-ci.com/g/danaketh/SMSManager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/danaketh/SMSManager)
[![Downloads](https://poser.pugx.org/danaketh/sms-manager/d/total.svg)](https://packagist.org/packages/danaketh/sms-manager)
[![Code Coverage](https://scrutinizer-ci.com/g/danaekth/SMSManager/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/danaketh/SMSManager)
[![GitHub Issues](https://img.shields.io/github/issues/danaketh/SMSManager.svg?style=flat-square)](https://github.com/danaketh/SMSManager/issues)

Library for PHP that can send SMS messages via SmsManager.cz gateway. PHP 8.2+ fork of [SMSManager](https://github.com/simPod/SMSManager). _(not all API methods are implemented for now)_

Installation
------------

```sh
composer require danaketh/sms-manager
```

Usage
-----

Use `SmsManager` interface in your code, eg.:

```php
public function __construct(SmsManager $smsManager) {
    ...
}
```

and alias SMSManager to `ApiSmsManager` for your production usage. 

Example with Symfony:

```yaml
services:
    SimPod\SmsManager\SmsManager:
        alias: SimPod\SmsManager\ApiSmsManager
```

To send message, create new `SmsMessage`:

```php
$smsMessage = new SmsMessage(
    'message text',
    [
        '+420777888999'
    ],
    RequestType::getRequestTypeLow() // optional, defaults to High
    'sender' // optional
    1 // optional, CustomID
);

$smsManager->send($smsMessage);
```

Third parameter of `SmsMessage` is [RequestType](https://smsmanager.cz/api/xml#noteRequestType) that is represented by `RequestType` class. Eg. low request type is instantiated with `RequestType::getRequestTypeLow()`.
