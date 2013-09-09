<?php
session_start();
if (!isset($_SESSION['access']) || $_SESSION['access'] == '') {
	header('Location: login.php');
}
$file = 'configuration.ini';
if (!$settings = parse_ini_file($file, TRUE))
	throw new exception('Unable to open ' . $file . '.');
$basedir = $settings["base"]["datadir"];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>FiKi - The File based Wiki</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="images/favicon.png"> 
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootswatch.min.css">
    <link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.10.3.custom.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
    <script src="js/jquery.js"></script>
    <script src="js/bsa.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootswatch.js"></script>
  </head>
  <body>

    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="index.php" class="navbar-brand"><img width="80px" src="images/logo.png"/></a>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="http://freeipa.labothink.fr/" target="_blank"><?php echo $_SESSION['user']?></a></li>
            <li><a href="login.php?out=true">Logout</a></li>
          </ul>

        </div>
      </div>
    </div>
<?php
if (isset($_GET["entry"]) and isset($_GET["subentry"])) {
    $entry = $_GET["entry"];
    $subentry = $_GET["subentry"];
?>    
<div class="container">
 <div class="bs-docs-section clearfix" style="margin-top: 35px;">
  <div class="row">
   <div class="col-lg-12">
<?php
echo "<a href='index.php?entry=".$entry."'>Back</a><br/><br/>";
echo(file_get_contents($basedir . '/' . $entry . "/html/" . $subentry.".html"));
?>
   </div>
  </div>
 </div>
</div>
<?php
} else if (isset($_GET["entry"]) and !isset($_GET["subentry"])) {
?>	
<div class="container">
 <div class="bs-docs-section clearfix">
  <div class="row">
   <div class="col-lg-4">
<?php
	$entry = $_GET["entry"];
	$metadata_file = $basedir . '/' . $entry . '/metadata.yaml';
	if (is_file($metadata_file)) {
	   $metadata=yaml_parse_file($metadata_file);
	   if (isset($metadata["title"])) {
           	echo('<h3>' . $metadata["title"] . '</h3>');    
	   } else {
   	        echo('<h3>' . $entry . '</h3>');
	   }
	} else {
	   echo('<h3>' . $entry . '</h3>');
	} 
	echo '<div class="bs-example">';
	echo '<table>';
	$path = $basedir . '/' . $entry . "/html";
	$path_pdf = $basedir . '/' . $entry . "/pdf";
	if ($subdir_handle = opendir($path)) {
		while (false !== ($subentry = readdir($subdir_handle))) {
			if ($subentry == "." or $subentry == "..") {
				continue;
			}
			$sub_path = $path . '/' . $subentry;
			if (preg_match('/<title>(.+)<\/title>/', file_get_contents($sub_path), $matches) && isset($matches[1])) {
				$title = $matches[1];
			} else {
				$title = $subentry;
			}
			$file_pdf = $path_pdf . '/' . preg_replace('/\.html$/', ".pdf", $subentry);
			echo '<tr>';
			echo '<td class="list-group-item"><a href="index.php?entry=' . $entry . '&subentry=' . preg_replace('/\.html$/', "", $subentry) . '">' . $title . '</a></td>';
			if (file_exists($file_pdf))
				echo '<td><a href="show.php?entry=' . $entry . '&subentry=' . preg_replace('/\.html$/', "", $subentry) . '" target="_blank"><img width="40px" src="images/pdf_icon.png"/></a></td></tr>';
			else
				echo '<td>&nbsp;</td>';
			echo '</tr>';

		}
	}
	echo '</table>';
	echo '<br/><br/><a href="index.php">Back</a>';
	echo '</div>';
?>   	
   </div>
  </div>
 </div>
</div>	
<?php
} else {
?>
    <div class="container">
<div class="bs-docs-section clearfix">
<div class="row">
          <div class="col-lg-6">
		<h1>Arguments List</h1>
<?php

$arguments = array();
if ($handle = opendir($basedir)) {
	while (false !== ($entry = readdir($handle))) {
		if ($entry == "." or $entry == "..") {
			continue;
		}
		$path = $basedir . '/' . $entry . "/html";
		if (is_dir($path)) {		
			$arguments[] = $entry;
		}
	}
}
closedir($handle);
natsort($arguments);
foreach($arguments as $arg) {
        $metadata_file = $basedir . '/' . $arg . "/metadata.yaml";
	echo "<div class='bs-example'>";
	if (is_file($metadata_file)) {
	   $metadata=yaml_parse_file($metadata_file);
	   if (isset($metadata["title"])) {
           	echo('<a href="index.php?entry='.$arg.'"><h3>' . $metadata["title"] . '</h3></a>');    
	   } else {
   	        echo('<a href="index.php?entry='.$arg.'"><h3>' . $arg . '</h3></a>');
	   }
           if (isset($metadata["description"])) {
		echo "<p>" . $metadata["description"] . "</p>";
	   }
	} else {
	   echo('<a href="index.php?entry='.$arg.'"><h3>' . $arg . '</h3></a>');
	} 
	echo "</div>";
	
}
?>
</div></div>
    </div>
    </div>
<?php } ?>
  </body>
</html>
