function send_sms () {
    if [[ ( "$#" -ne 2 ) ]]; then
        echo "Usage :"
        echo "send_sms <mobile_number> <text_msg>"
        echo ""
        echo "Do NOT add dialing prefix like +91 or so!"
        echo ""
        return 1;
    fi

    currdir=`pwd`
    cd ~/.way2sms/
    echo "$1" | awk -v t="$2" -v d="`date`" '{split($0, n, ","); for(i in n) print n[i], "\t[ ", d, " ]\t:\t", t;}' >> .smslog

    cat > console.php << __PHP_EOF__
<?php
    require('./libway2sms.php');

    \$auth = explode("\n", file_get_contents(".way2smsAuth"));

    \$res = sendSMS ( trim(\$auth[0]) , trim(\$auth[1]) , "$1" , "$2" );
    foreach(\$res as \$result) {
        if(\$result['result'] == true) {
            echo "SMS Successfully sent to " . \$result['target'] . "\\n";
        } else {
            echo "SMS could not be sent to " . \$result['target'] . "\\n";
        }
    }
?>
__PHP_EOF__

    /opt/lampp/bin/php -f console.php

    wait
    \rm -f console.php
    cd "$currdir"
}
