#!/bin/bash

mkdir ~/.way2sms
cp libway2sms.php ~/.way2sms

cat bash/send_sms.sh >> ~/.bashrc

echo -n " ($) Please enter your Way2SMS username and press [ENTER] : "
read name
echo -n " ($) Please enter your password and press [ENTER] : "
read pass

echo $name:$pass > ~/.way2sms/.way2smsAuth

echo -ne "libway2sms-console has now been installed.\n"
echo -ne "You can change your authentication details in file ~/.way2sms/.way2smsAuth\n"
echo -ne "Type 'send_sms' for more info on usage\n"
