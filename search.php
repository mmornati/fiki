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
<div class="container">
 <div class="bs-docs-section clearfix">
  <div class="row">
   <div class="col-lg-12">
<?php
/**
 * PHP Script Developed by Sampath Nannam and Cinu Chacko to Scan a directory and display  the information
 *
 * (some functions are PHP 5 specific) 
 */

// time when this script starts 
$startTime = time();

// create object of the class
require_once('ClassGrepSearch.inc.php');
require_once('exampleSearchCustomFunctions.php');
$classGrepSearch = ClassGrepSearch::getInstance();

// The extentions to be searched (in this example, the extentions are comma seperated)
$filesWithExtentionsToBeSearched = "txt, html"; 



// the path of the directory to be searched 
$scanDir = $settings["base"]["datadir"];

// set the value for the string to be searched 

if(isset($_POST['queryParam'])) {
    $searchString = $_POST['queryParam'];
}
echo "---->" . $_POST['queryParam'];
/*
if(isset($_POST['spanbegin'])) {
	
    	$startSpan = $_POST['spanbegin'];
}

if(isset($_POST['spanend'])) {
    $endSpan = $_POST['spanend'];
}

if(isset($_POST['limit'])) {
    $limit = $_POST['limit'];
}

if(isset($_POST['searchtype'])) {
    $searchType = $_POST['searchtype'];
}

if(isset($_POST['casesensitive'])) {
    $caseSensitive = $_POST['casesensitive'];
}

if(isset($_POST['searchcountonly'])) {
    $searchCountOnly = $_POST['searchcountonly'];
}
*/
$searchType="all";
$limit="none";
$caseSensitive="no";
$searchCountOnly="no";
// creates an array of all the provided extentions
$classGrepSearch->createArrayOfExtentions(",",$filesWithExtentionsToBeSearched);
// Sets the search type
$classGrepSearch->setSearchType($searchType);
$classGrepSearch->setSearchString($searchString);
$classGrepSearch->setScanDir($scanDir);
$classGrepSearch->setCaseSensitive(($caseSensitive=="yes")?true:false);
$fileCounter = $classGrepSearch->readDir($scanDir);

// print information
echo "<HR> <H3> <center> Search Results !</center></H3>";
echo "<HR> The pattern/string '<font color='red'><b>".$classGrepSearch->getSearchString()."</font></b>' was found in following <font color='Green' ><b> $fileCounter  </b> </font>file(s): <BR>";
$arrayOfFilenames = $classGrepSearch->getarrayOfFilenames();
$arrayOfTitles = $classGrepSearch->getTitles();
$arrayOfEntries = $classGrepSearch->getEntries();
$arrayOfSubentries = $classGrepSearch->getSubEntries();
for($i=0,$j=0;$i<sizeof($arrayOfFilenames);$i++) 
{
    $fileName = str_replace($_SERVER['DOCUMENT_ROOT'],"",$arrayOfFilenames[$i]);
    $linkName = str_replace($_SERVER['DOCUMENT_ROOT'],"Z:",$arrayOfFilenames[$i]);
    $classGrepSearch->setGlobalCount(0);
    if($searchCountOnly !="yes")
    {
       $htmlLines = createLinesFromFile($scanDir.$fileName,$classGrepSearch);
    }
    else
    {
      $classGrepSearch->setGlobalCount( $classGrepSearch->getSearchCount($scanDir.$fileName));
    }
	if($htmlLines !=""||$searchCountOnly =="yes")
	{
		echo "<BR><b> # ".(($j++)+1).") </b><font color='green'><b> <a href='index.php?entry=".$arrayOfEntries[$i]."&subentry=".$arrayOfSubentries[$i]."' style='color:green;text-decoration:none'> ".$arrayOfTitles[$i]." </a> </font></b> [".$classGrepSearch->getGlobalCount(). " time(s)]";
		echo "<BR>".$htmlLines;
	}
}

//calulate the time (in seconds) to execute this script
$endTime = time();
$totalTime = ($endTime - $startTime);

// total time taken to execute this script
$timeTaken = $classGrepSearch->convertSecToMins($totalTime);

echo "<BR><BR><hr><center><h4 >Info: Searched in <font color='blue'>".sizeof($classGrepSearch->getDirFile())."</font> Files in <font color='blue'>".sizeof($classGrepSearch->getDirArray())."</font> directories. </h4><hr>Total time taken: <font color='blue'> $timeTaken </font> </center><HR><center></b>";

?>
</div></div>
</div> </div>
 </body>
</html>
