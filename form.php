<html>
<head>
	<meta charset = "utf-8">
</head>
<body>
<form action = "<?php echo $_SERVER['PHP_SELF']?>" method="POST" class="userForm" enctype="multipart/form-data">
	<input type= "radio" id = "unpack" name = "zip" value = "wypakuj" required>
	<label for="unpack">wypakuj</label>
	<input type= "radio" id = "pack" name= "zip" value = "zapakuj" required>
	<label for="pack">zapakuj</label>
	<input type="text" placeholder="zip name" name="zipName" requied>
	<input type="submit" value="Przeslij" >
	<input type="file" name="file[]" multiple required > 
</form>
</body>
</html>

<?php
include("menu.php");
$PageLoad = array(
	'formLoaded' => date("Y-m-d H:i:s"),
	'Dzialanie' => "Zaladowanie formularza",
	'url' => "form.php",
);

$formUnpack = array(
	'formLoaded' => date("Y-m-d H:i:s"),
	'Dzialanie' => "Wypakowano plik/i",
	'url' => "form.php",
);
$formPack = array(
	'formLoaded' => date("Y-m-d H:i:s"),
	'Dzialanie' => "Zapakowano plik/i",
	'url' => "form.php",
);
array_pop($_SESSION['journal']);
array_push($_SESSION['journal'], $PageLoad);
class Zip {
	private function zip( $path="pack", $file_path=array("test.txt")) {
		$zip = new ZipArchive();
		if( $zip->open( $path, ZipArchive::CREATE)!==TRUE ){
			echo "Error opening $path";
		} else {
			echo "Packed: <br> ";
			for($i = 0; $i<count($file_path); $i++) {
				$zip->addFile( "src/".$file_path[$i], $file_path[$i] );
				echo $file_path[$i];
				echo "<br>";
				$formPack = array(
					'formLoaded' => date("Y-m-d H:i:s"),
					'Dzialanie' => "Zapakowano plik: ".$file_path[$i],
					'url' => "form.php",
				);
				array_push($_SESSION['journal'], $formPack);
			}
			$zip->close();
			echo "zip: ".$path."<br>";
			echo "Zapakowano pomyślnie";
		}   
	}
	private function unzip( $path="pack", $target_path="unpack", $files=array('test.txt') ) {
		$uzi = new ZipArchive();
		if( $uzi->open( $path ) ) {
			echo $uzi->getArchiveComment();
			for($i=0; $i<$uzi->numFiles; $i++){
				$file = $uzi->getNameIndex($i);
				if( in_array( $file, $files )) {
					$uzi->extractTo( $target_path , $file);
					$formUnpack = array(
						'formLoaded' => date("Y-m-d H:i:s"),
						'Dzialanie' => "Wypakowano plik: ".$file,
						'url' => "form.php",
					);
					array_push($_SESSION['journal'], $formUnpack);
				}
			}
			echo "Pomyślnie wypakowano";
			
		} else {
			echo "Error opening $path";
		}
		$uzi->close();
	}

	private function moveFile() {
		for($i=0; $i<count($_FILES['file']['name']); $i++) {
			$fileName = $_FILES['file']['name'][$i];
			move_uploaded_file($_FILES["file"]["tmp_name"][$i], "src/".$fileName);
			$formUploaded = array(
				'formLoaded' => date("Y-m-d H:i:s"),
				'Dzialanie' => "Przesłano plik: ".$fileName,
				'url' => "form.php",
			);
			array_push($_SESSION['journal'], $formUploaded);
		}
	}

	public function init() {
		if(!file_exists("pack"))
		{
			mkdir("pack", 0777);
		}
		if(!file_exists("src"))
		{
			mkdir("src", 0777);
		}
		if(!file_exists("unpack"))
		{
			mkdir("unpack", 0777);
		}
		if(!file_exists("upload"))
		{
			mkdir("upload", 0777);
		}
		echo "<br>";
		if(isset($_POST['zip'])) {
			if( $_POST['zip'] == "zapakuj" ) 
			{
				if( $_POST['zipName'] != null ) {
				   $this->moveFile();
					$this->zip( "pack/".$_POST['zipName'].".zip", $_FILES["file"]['name']);
				} else {
					echo "<br>Nie podano nazwy archiwum";
				}
			}
		} 

		if( isset($_GET['check']) ) {
			$files = array();
			for($i = 0; $i <= count($_GET)+10; $i++) {
				if(isset($_GET['chBox'.$i])) {
					array_push($files, $_GET['chBox'.$i]);
					unset($_GET['chBox'.$i]);
				}
			}
			$this->unzip( "src/phpZip.zip", "unpack", $files );
			unset($_GET["check"]);
		} else {
			if(isset($_POST['zip']))
			{
				if(($_POST['zip']) == "wypakuj") {
					for($i=0; $i<count($_FILES['file']['name']); $i++) {
						if( strpos( $_FILES["file"]['name'][$i], ".zip" ) ) {
							echo "<br>Wybierz pliki do rozpakowania: <br><form action = '".$_SERVER['PHP_SELF']."' method='GET' class='userForm' enctype='multipart/form-data'>";
							$this->moveFile();
							$tmp = new ZipArchive();
							$tmp->open( "src/".$_FILES["file"]['name'][$i] );
							echo "<input type='checkbox' checked name='check' style='display:none' value='".$_FILES["file"]['name'][$i]."' ><br>";
							for( $i = 0; $i < $tmp->numFiles; $i++ ){ 
								$stat = $tmp->statIndex( $i ); 
								echo "<input type='checkbox' name='chBox".$i."' value='".$stat['name']."' >".$stat['name']."<br>";
							}
							echo "<br><input type='submit' value='Wybierz' ></form>";
						} else {
							echo "<br>Nie wybrano pliku o rozszerzeniu zip";
						}
					}
				}
			}
		}
	}

}
$zip = new Zip;
$zip->init();


?>