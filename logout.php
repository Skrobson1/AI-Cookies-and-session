<?php
include("menu.php");
$PageLoad = array(
    'formLoaded' => date("Y-m-d H:i:s"),
    'Dzialanie' => "Zaladowanie formularza",
    'url' => "logout.php",
);
array_pop($_SESSION['journal']);
array_push($_SESSION['journal'], $PageLoad);
array_push($_SESSION['journal'], $logOut);
$Name = 'logger';
$val = 1;
$host = 'localhost';
$time = 3600;
$expTime = time()-$time;
$Name = 'usrId';
$val = 15;
setcookie( $Name, $val, $expTime, "/", $host, false, false );

$_SESSION['usrId'] = 0;
unset($_SESSION['usrName']);

header("Location: demo.php");
?>
