<?php

/**
 * sendSMS
 * This function sends sms to one or more people via cURLing way2sms interface
 *
 * @author Saswat Padhi
 * @url https://github.com/SaswatPadhi/libway2sms
 *
 * @param String $user The Way2SMS username of the sender.
 * @param String $pass The Way2SMS password of the sender.
 * @param String $msgs The text to be sent to all recepients.
 * @param String $whom The list of 10-digit Indian numbers, separated by SPACE, comma or semicolon.
 *
 * @return String/Array Error string or array or results for each sms.
 *
 * @example sendSMS ('9889668998', 'thisismypassword', '7898679876 8272397440', 'Sent via Way2SMS');
 *
 * DISCLAMIER:
 * I (Saswat Padhi), am in no way responsible for illegal use of the following code.
 **/

function sendSMS ($user, $pass, $whom, $msgs)
{
    $msgs = trim($msgs);
    if (strlen($msgs) == 0)
        return "ERROR :: Empty message!";
    $user = urlencode($user);
    $pass = urlencode($pass);
    $break = mb_convert_encoding('&#x205E;', 'UTF-8', 'HTML-ENTITIES');
    $msgarr = array_values(array_filter(array_map('trim', explode($break, chunk_split($msgs, 140, $break)))));

    $res = array();
    $curl = curl_init();

    // Something is wrong with site3.
    $siteid = 3;
    while ($siteid == 3)
        $siteid = rand(1, 12);

    $curlOpts = array(
        CURLOPT_POST            =>  1,
        CURLOPT_COOKIESESSION   =>  1,
        CURLOPT_FOLLOWLOCATION  =>  1,
        CURLOPT_RETURNTRANSFER  =>  1,
        CURLOPT_MAXREDIRS       =>  20,
        CURLOPT_CONNECTTIMEOUT  =>  30,
        CURLOPT_COOKIEFILE      =>  "cookie_way2sms",
        //CURLOPT_PROXY           =>  "http://netmon.iitb.ac.in:80/",
        //CURLOPT_PROXYUSERPWD    =>  "username:password",
        CURLOPT_URL             =>  "http://site".$siteid.".way2sms.com/login.action",
        CURLOPT_POSTFIELDS      =>  "username=".$user."&password=".$pass."&button=Login",
        CURLOPT_REFERER         =>  "http://site".$siteid.".way2sms.com/content/index.html",
        CURLOPT_USERAGENT       =>  "Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:12.0) Gecko/20100101 Firefox/12.0"
    );
    curl_setopt_array($curl, $curlOpts);
    $content = curl_exec($curl);

    if (curl_errno($curl))
        return "ERROR :: Could not connect to Way2SMS. (". curl_error($curl).")";

    $main = stripos(curl_getinfo($curl, CURLINFO_EFFECTIVE_URL), "Main.action");
    if ($main === false || $main == 0)
        return "ERROR :: Login error. Please recheck credentials.";

    curl_setopt($curl, CURLOPT_REFERER, curl_getinfo($curl, CURLINFO_EFFECTIVE_URL));
    curl_setopt($curl, CURLOPT_URL, "http://site".$siteid.".way2sms.com/jsp/InstantSMS.jsp");
    $content = curl_exec($curl);

    preg_match_all('/<input[\s]*type="hidden"[\s]*name="Action"[\s]*id="Action"[\s]*value="?([^>]*)?"/si', $content, $match);
    $action = $match[1][0];

    $arr = array_values(array_filter(array_map('trim', preg_split("/[\s]*[,;\s][\s]*/", $whom))));
    foreach ($arr as $num)
    {
        if (strlen($num) != 10 || !is_numeric($num) || strpos($num, ".") != false)
        {
            $res[] = array('number' => $num, 'text' => urldecode($msgs), 'result' => "ERROR :: Invalid number!");
            continue;
        }
        $num = urlencode($num);

        foreach ($msgarr as $msg) {
            curl_setopt($curl, CURLOPT_URL, 'http://site'.$siteid.'.way2sms.com/onesms.action');
            curl_setopt($curl, CURLOPT_REFERER, curl_getinfo($curl, CURLINFO_EFFECTIVE_URL));
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "HiddenAction=instantsms&catnamedis=Birthday&chkall=on&Action=".$action."&MobNo=".$num."&textArea=".$msg);
            $contents = curl_exec($curl);

            $pos = strpos($contents, 'Message has been submitted successfully');
            if($pos === false)
                break;
            usleep(200*1000);
        }
        $res[] = array('target' => $num, 'text' => urldecode($msgs), 'result' => ($pos !== false));
    }

    curl_setopt($curl, CURLOPT_URL, "http://site".$siteid.".way2sms.com/LogOut");
    curl_setopt($curl, CURLOPT_REFERER, curl_getinfo($curl, CURLINFO_EFFECTIVE_URL));
    $content = curl_exec($curl);

    curl_close($curl);
    return $res;
}

?>
