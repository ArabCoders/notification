# Notification Manger

A Base Notification Manager that abstract away all the providers differences under common Interface.

## Install

Via Composer

```bash
$ composer require arabcoders/notification
```

## Usage Example.

```php
<?php

require __DIR__ . '/../../autoload.php';

$provider = new \arabcoders\notification\Providers\PushOver();
$provider->setKey('key');

$notification = new arabcoders\notification\Notification( $provider );

$notification->setTokens( [ 'token here' ] )
             ->setTitle( 'title' )
             ->setMessage( 'message' )
             ->send();
```
