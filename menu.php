<?php
error_reporting(0);
if( session_status() == PHP_SESSION_NONE ) {
	session_start();
	if(!isset($_SESSION['journal'])) {
		$_SESSION['journal'] = array();
	}
	
	$logIn = array(
		'formLoaded' => date("Y-m-d H:i:s"),
		'Dzialanie' => "Zalogowano: ".$_SESSION['usrName'],
		'url' => "login.php",
	);
	$logOut = array(
		'formLoaded' => date("Y-m-d H:i:s"),
		'Dzialanie' => "Wylogowano: ".$_SESSION['usrName'],
		'url' => "logout.php",
	);
	$logInError = array(
		'formLoaded' => date("Y-m-d H:i:s"),
		'Dzialanie' => "Błąd Logowania",
		'url' => "login.php",
	);
	
}
else 
	echo 'Sesja nie wystartowała';

$PageLoad = array(
	'formLoaded' => date("Y-m-d H:i:s"),
	'Dzialanie' => "Zaladowanie formularza",
	'url' => "menu.php",
);
array_push($_SESSION['journal'], $PageLoad);
$uid = isset($_COOKIE['usrId']) ? $_COOKIE['usrId'] : 0;
((int)$uid==13) ? $_SESSION['usrName']='dRoot' : null;
$user = array_key_exists('usrName',$_SESSION) ? $_SESSION['usrName'] : false;
if($user){
	$logLink = '<a href="logout.php">Logout ('.$user.')</a>';
} else
	$logLink = '<a href="login.php">Login</a>';
echo '<nav>
	<a href="demo.php">Main</a>
	<a href="form.php">Form</a>
	'.$logLink.'
	<a href="logger.php">Logger</a>
</nav>';
?>
