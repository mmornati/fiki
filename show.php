<?php
session_start();
if (!isset($_SESSION['access']) || $_SESSION['access'] == '') {
       header('Location: login.php');
}
$file='configuration.ini';
if (!$settings = parse_ini_file($file, TRUE)) throw new exception('Unable to open ' . $file . '.');
if (isset($_GET["entry"]) and isset($_GET["subentry"])) {	
	$basedir = $settings["base"]["datadir"];
    $entry = $_GET["entry"];
    $subentry = $_GET["subentry"];
	
	$path_pdf = $basedir.'/'.$entry."/pdf";
	$filename = $subentry . ".pdf";
	$file_pdf = $path_pdf.'/'.$filename;	
	
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="' . $filename . '"');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . filesize($file_pdf));
	header('Accept-Ranges: bytes');
	
	@readfile($file_pdf);
} else {
	echo "Error. Not enough information to show the file content!";
}
?>
