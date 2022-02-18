<?php
include("menu.php");
$PageLoad = array(
    'formLoaded' => date("Y-m-d H:i:s"),
    'Dzialanie' => "Zaladowanie formularza",
    'url' => "logger.php",
);
array_pop($_SESSION['journal']);
array_push($_SESSION['journal'] , $PageLoad);


if(isset($_POST['enableLog'])) {
    $lName = 'logger';
    $val = 1;
    $host = 'lo.local';
    $time = 3600;
    $expTime = time()+$time;
    setcookie( $lName, $val, $expTime, "/", $host, false, false );
    header("Refresh:0");
}

if(isset($_POST['disableLog'])) {
    $lName = 'logger';
    $val = 1;
    $host = 'lo.local';
    $time = 3600;
    $expTime = time()-$time;
    setcookie( $lName, $val, $expTime, "/", $host, false, false );
    header("Refresh:0");
}

if(!isset($_COOKIE['logger'])) {
    echo "<form action = '".$_SERVER['PHP_SELF']."' method='POST' >
    <input type='submit' name='enableLog' value='Włącz ciasteczka'><br></form>";
} else {
    
    if($_COOKIE['logger'] == 1) {
        echo "
            <form action = '".$_SERVER['PHP_SELF']."' method='POST' >
            <label >Czyszczenie sesji</label> <input type='submit' name='sessionDelete' value='Wyczyść'><br>
            <label>Czyszczenie ciasteczka</label><input type='submit' name='cookieDelete' value ='Wyczyść'><br>
            <label>Zapisz dziennik zdarzeń do pliku</label><input type='submit' name='save' value ='Zapisz'><br>
            <input type='submit' name='disableLog' value='Wyłącz ciasteczka'><br></form>
            </form>
        ";
        showLogs();
        if(isset($_POST['sessionDelete'])) {
            unset($_SESSION['journal']);
            //$_SESSION['journal'] = array();
            header("Refresh:0");
        }
        if(isset($_POST['cookieDelete'])) {
            $host = 'localhost';
            $time = 3600;
            $expTime = time()-$time;
            $Name = "usrId";
            $val = 11;
            setcookie( $Name, $val, $expTime, "/", $host, false, false );
            header("Refresh:0");

        }
        if(isset($_POST['save'])) {

            $fp = fopen('journal.csv', 'w');

            foreach($_SESSION['journal'] as $fields){
                fputcsv($fp, $fields);
            }
            fclose($fp);

        }
    }
}

function showLogs() {
    foreach($_SESSION['journal'] as $fields){
        foreach($fields as $field){
            echo " ".$field." ";
        }
        echo "</br>";
    }
}
?>
