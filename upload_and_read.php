<?php
session_start();
$title = "Select Your Modifications";
include "header.php";

//Load functions
require 'php_libraries/upload.php';
require 'php_libraries/file_reading.php';
require 'php_libraries/utilities.php';

//Set filename variable
$_SESSION["original_filename"] = basename($_FILES["fileToUpload"]["name"]);

//Initiate log file
startLogFile($_SESSION["original_filename"]);

//Upload file
$target_dir = "uploads/";
$target_file = $target_dir . $_SESSION["original_filename"]; // Security vulnerability. If only 1 file at a time, just save as a dummy name.  //$target_file = "dummy.xml";
upload($target_file);

//Get data from file
$_SESSION["affiliateProviderNames"] = getOldProviderNames($target_file);
$_SESSION["numberOfOldProviders"] = count($_SESSION["affiliateProviderNames"]);
$_SESSION["voanlProviderNames"] = getNewProviderNames();
$_SESSION["numberOfNewProviders"] = count($_SESSION["voanlProviderNames"]);
$XML_Tags = getTopLevelTags($target_file);
$numberOfTags = count($XML_Tags);

//Create user input form
?>

<form action="write_new_file.php" method="post">
    <b>Check the boxes corresponding to the top level elements that you wish to exclude from your new file:</b><br />
    <?php 
	for($x = 0; $x < $numberOfTags; $x++) {
		echo '<input type="checkbox" name="tagsToRemove[]" value="' . $XML_Tags[$x] . '">' . $XML_Tags[$x] . '<br />';
	}
	?>
	<hr /><br /><b>Configure the provider mapping:</b><br />
	<?php
	echo '<table>';
	for($x = 0; $x < $_SESSION["numberOfOldProviders"]; $x++) {
		echo '<tr><td>';
		//echo '<input type="checkbox" name="tagsToRemove[]" value="' . $_SESSION["affiliateProviderNames"][$x] . '">' . $_SESSION["affiliateProviderNames"][$x];
		echo $_SESSION["affiliateProviderNames"][$x];  //replace with line above to include checkboxes.
		echo '</td><td>';
		echo '<select name="' . $x . '">';
		//echo '<select name="' . $_SESSION["affiliateProviderNames"][$x] . '">';    //alternate for previous line, might not work.
		for($y = 0; $y < $_SESSION["numberOfNewProviders"]; $y++) {
			echo '<option value="' . $_SESSION["voanlProviderNames"][$y] .  '">' . $_SESSION["voanlProviderNames"][$y] . '</option>';
		}
		echo '</td></tr>';
	}
	echo '</table>';
	?>
	<input type="submit" value="Write New File" name="submit">
</form>

<?php
include "footer.php";
?>