libway2sms
============
A PHP function to send SMS to any mobile number in India, by cURLing Way2SMS. Way2SMS keeps changing their interface every now and then
but this function would adapt to most simple changes that Way2SMS implements. <sup>[#](https://github.com/SaswatPadhi/libway2sms#trivial-changes-to-way2sms-interface)</sup>

### Background ###
------------------
Way2SMS is one of the (few good) online services allowing Indian users to send SMS messages to any Indian mobile for free.
But their website is full of annoying ads and several interface bugs, which makes sending an SMS really inconvenient for users.
Previously, there have been several attempts at writing a better (unofficial :-P) interface for Way2SMS, but ...

* They are not actively maintained. Some of them have not been modified since 2009!
* The interface is mostly hard-coded. So, they keep breaking down every now and then.
* They are not very feature rich. Some of them do not allow sending message to multiple numbers simultaneously.
  Most of them do not allow sending messages more than 140 character in length.

### Features of libway2sms ###
------------------------------
* (Virtually) No more limitation on length of message!
* Send SMS to multiple mobile numbers by a single function call.
* Supports new line characters and support for sending multi-line messages.
* Has better detection techniques that would adapt to slight changes in the Way2SMS interface. <sup>[#](https://github.com/SaswatPadhi/libway2sms#trivial-changes-to-way2sms-interface)</sup>

### How to use libway2sms ###
-----------------------------
Really simple. Just `require` the library file, and `sendSMS`:
```php
<?php
    require("libway2sms.php");
    sendSMS('9876543210', 'drowssap', '9876543210', // notice the multi-line message below.
    "Hello XYZ!
I am sending this message via Way2SMS.
(But I saved myself from the annoying ads)");
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

### Using libway2sms to SMS from bash ###
-----------------------------------------
1. Run `install.sh` on your system.
2. Enter authentication details when asked.
3. Use `send_sms` to send sms from your terminal.

```bash
    send_sms "3836854975" "This is sent from my terminal."
```

**NOTE**:
* You should have PHP installed on your machine for this script to work. (Should be @ `/opt/lampp/bin/php` or you should change the path in `install.sh`)
* You can change your authentication details in the file `~/.way2sms/way2smsAuth`.

### .: DISCLAIMER :. ###
------------------------
If you intend to use this piece of code, only **YOU** are responsible for what you use this code for.
The author does not take any responsibility for any kind of misuse of this code.

<br><br><br>

#### Trivial changes to Way2SMS interface ####
----------------------------------------------
Since the initial commit of libway2sms, I have seen Way2SMS making the following changes to their interface:

1.  [`25th May`](https://github.com/SaswatPadhi/libway2sms/commit/d386bcd76de4e521c52ca0831ca1b5b2eedfee53)   Just renamed the files:
    - Login1.action     -->     login.action
    - quicksms.action   -->     onesms.action

2.  [`26th May`](https://github.com/SaswatPadhi/libway2sms/commit/f51be03e2a8d89f2e6f3505751c4e25791f56185)   Just renamed the files:
    - login.action      -->     w2slogin.action
    - onesms.action     -->     w2sonesms.action

3.  [`28th May`](https://github.com/SaswatPadhi/libway2sms/commit/161e2b37cc974e894c329097407ccfc3788cb254)   Just renamed the files:
    - w2slogin.action      -->     Login1.action
    - w2sonesms.action     -->     quicksms.action

