<?php
session_start();
$title = "Receive Your New File";
include "header.php";

//Load functions
require 'php_libraries/file_modification.php';
require 'php_libraries/utilities.php';

//Set variables
$original_filename = $_SESSION["original_filename"];

$target_dir = "uploads/";
$intermediate_dir = "intermediate_files/";
$new_dir = "new_files/";
$target_file = $target_dir . $original_filename;
$intermediate_file = $intermediate_dir . "intermediate_" . $original_filename;
$new_file = $new_dir . "new_" . $original_filename;

$arrayOfUnwantedTags = $_POST['tagsToRemove'];
$providerMapping = createProviderMapping($_SESSION["affiliateProviderNames"]);

//Display info for user
echo "Your original file is: " . $original_filename . "<br />";
echo "Your new file is: new_" . $original_filename . "<br /><br />";


if(empty($arrayOfUnwantedTags))  {
    echo("You didn't select any tags. No sections will be removed from your file.");
  }
  else  {
    $N = count($arrayOfUnwantedTags);
    echo("You selected $N sections(s) for removal: <br />");
    for($i=0; $i < $N; $i++)    {
      echo($i+1 . ". " . $arrayOfUnwantedTags[$i] . " <br />");
    }
  }


echo("<br /><br />Your provider names will be replaced according to this mapping: <br />");
echo '<table border="1">';
echo '<tr><td><b>Old Name (Affiliate)</b></td><td><b>New Name (National)</b></td></tr>';
for($x = 0; $x < $_SESSION["numberOfOldProviders"]; $x++) {
	echo '<tr><td>';
	echo $_SESSION["affiliateProviderNames"][$x];
	echo '</td><td>';
	echo $providerMapping[$_SESSION["affiliateProviderNames"][$x]];
	echo '</td></tr>';
}
echo '</table>';


//Write the new file
removeTopLevelSections($target_file,$intermediate_file,$arrayOfUnwantedTags,$_SESSION["log_filename"]);
changeProviderNames($intermediate_file,$new_file,$providerMapping,$_SESSION["log_filename"]);

//Display download links for user
echo "<br />Your new file has been written.<br />";
echo '<a href="' . $new_file . '" download>Click here to download your new XML file.</a><br />';
echo '<a href="' . $_SESSION["log_filename"] . '" download>Click here to download your log file.</a><br />';

include "footer.php";
?>