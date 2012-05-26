libway2sms
============
A PHP function to send SMS to any mobile number in India, by cURLing Way2SMS. Way2SMS recently made some changes to their
interface and this code is tested to be working prefect (as of 26th May, 2012).

Features
----------
* Works with the latest Way2SMS interface.
* No limitation on length of message!
* Send SMS to multiple mobile numbers by a single function call.

Using libway2sms
------------------
Really simple. Just `require` the library file, and `sendSMS`:
```php
<?php
    require("libway2sms.php");
    sendSMS('9876543210', 'drowssap', '9876543210', 'Hello me!');
?>
```

Optionally, you can verify if the messages ware successfully sent:
```php
<?php
    require("libway2sms.php");
    $result = sendSMS('9876543210', 'drowssap', '9876543210, 1234567890', 'Way2SMS rocks!');
    foreach($result as $res) {
        if($res['result'] === true)
            echo "<br>SMS Successfully sent to ".$res['target'];
        else
            echo "<br>SMS could not be sent to ".$res['target'];
    }
?>
```

.: DISCLAIMER :.
------------------
If you intend to use this piece of code, only **YOU** are responsible for what you use this code for.
The author does not take any responsiibility for any kind of misuse of this code.
