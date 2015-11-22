<?php

if($_SERVER["REQUEST_METHOD"] == "POST") 
{
    define('myyshop', true);
    session_start();
    if($_SESSION['img_captcha'] == strtolower($_POST['reg_captcha']))
    {
        echo 'true';
    } else {echo 'false';}
}

?>