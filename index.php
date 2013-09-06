<?php
session_start();
if (!isset($_SESSION['access']) || $_SESSION['access'] == '') {
       header('Location: login.php');
}
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
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <script src="js/bsa.js"></script>

    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="../" class="navbar-brand"><img width="80px" src="images/logo.png"/></a>
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


    <div class="container">
<div class="bs-docs-section clearfix">
<div class="row">
          <div class="col-lg-4">
<?php
$basedir = './data/';
if ($handle = opendir($basedir)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry == "." or $entry == "..") {
             continue;
        }
        $path = $basedir.'/'.$entry;
        if (is_dir($path)) {
	    echo '<h2 id="pagination">'.$entry.'</h2>';
            echo '<div class="bs-example">';
            echo '<ul class="list-group">';
	    if ($subdir_handle = opendir($path)) {
               while (false !== ($subentry = readdir($subdir_handle))) {
		  if ($subentry == "." or $subentry == "..") {
          	     continue;
	          }
                  $sub_path = $path.'/'.$subentry;
	          if (preg_match('/<title>(.+)<\/title>/', file_get_contents($sub_path),$matches) && isset($matches[1])) {
                       $title = $matches[1];
                  } else {
		       $title = $subentry;
		  }
		  echo '<li class="list-group-item"><a href="'.$sub_path.'">'.$title.'</a></li>';

               }
            }
            echo '</ul>';
            echo '</div>';

        }
    }

    closedir($handle);
}
?>
</div></div>
    </div>
    </div>


    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootswatch.js"></script>
  </body>
</html>
