<?php
include("menu.php");
$PageLoad = array(
    'formLoaded' => date("Y-m-d H:i:s"),
    'Dzialanie' => "Zaladowanie formularza",
    'url' => "demo.php",
);
array_pop($_SESSION['journal']);
array_push($_SESSION['journal'] , $PageLoad);
echo '<h3>Zagadnienia</h3>';
$li.= '<li>Obsługa sesji</li>';
$li.= '<li>Obsługa ciasteczek</li>';
$li.= '<li>Obsługa formulrza</li>';
echo '<ul>'.$li.'</ul>';
echo '<h3>Witaj '.($user?$user:'gościu').'!</h3>';
?>
