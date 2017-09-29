<?php

/********************************************************************************
Function: removeTopLevelSections
Takes a target filename, a new filename, a list of top-level XML tags, and a log file. Writes a new XML file with corresponding sections removed.
Deletes the target file from the server when done.
*********************************************************************************/
function removeTopLevelSections($target_XML_file,$new_XML_file,$tags,$log_file) {
	$xmlfile = fopen($target_XML_file, "r");
	$newfile = fopen($new_XML_file, "w");
	$logfile = fopen($log_file, "a");
	$lineCounter = 0;
	$tagCounter = 0;
	$currentTag = $tags[$tagCounter];
	$writingSwitch = 1;
//	for($x = 0; $x <= 10000; $x++) {		//	for testing large files
//	$validClosingTag = 1;  // work on this more, not used yet.  The content of some <note> tags includes line breaks.

	while(!feof($xmlfile)) {
		$lineCounter++;
		$currentLine = fgets($xmlfile);
		//If line contains opening tag, turn off writing.
		if (strpos($currentLine,$currentTag . ">") == 4) { // If the tagname begins at character number 5 (should be preceded by "   <")
			$writingSwitch = 0;
			fwrite($logfile,"At " . date("h:i:sa") . ", writing was turned off at line " . $lineCounter . ":\n");
			fwrite($logfile,$currentLine);
		}
		if ($writingSwitch == 1) {
			fwrite($newfile,$currentLine);
		}
		//Just for testing, nothing should be written here.
/*		if ($writingSwitch == 0) {
			fwrite($newfile,"000" . $currentTag . $currentLine);
		}*/
		//If line contains closing tag, turn on writing and increment counter.
		if (strpos($currentLine,$currentTag . ">") == 5) { // If the tagname begins at character number 6 (should be preceded by "   </")
			$tagCounter++;
			$currentTag = $tags[$tagCounter];
			$writingSwitch = 1;
			fwrite($logfile,"At " . date("h:i:sa") . ", writing was turned on after line " . $lineCounter . ":\n");
			fwrite($logfile,$currentLine . "\n");
		}
	}
	fwrite($logfile,"Intermediate step completed at " . date("h:i:sa") . "\n\n");
	fclose($xmlfile);
	fclose($newfile);
	fclose($logfile);
	unlink($target_XML_file); //done with original file, remove it from uploads directory.
}

/********************************************************************************
Function: changeProviderNames
Takes a target filename, a new filename, a key-value array to map the providers, and a logfile. Writes a new XML file with the provider <name> tags replaced.
Deletes the target file from the server when done.
*********************************************************************************/

function changeProviderNames($target_XML_file,$new_XML_file,$mapping,$log_file) {
	$xmlfile = fopen($target_XML_file, "r");
	$newfile = fopen($new_XML_file, "w");
	$logfile = fopen($log_file, "a");
	$lineCounter = 0;
	$providerCounter = 0;
	$currentProvider = $_SESSION["affiliateProviderNames"][$providerCounter];
	$providerNameSwitch = 0;
//	for($x = 0; $x <= 10000; $x++) {		//	for testing large files
//	$validClosingTag = 1;  // work on this more, not used yet.  The content of some <note> tags includes line breaks.
	while(!feof($xmlfile)) {
		$lineCounter++;
		$currentLine = fgets($xmlfile);
		if (strpos($currentLine,"<Provider ")==6){	
			$providerNameSwitch = 1;
		}
		if ($providerNameSwitch == 1 and substr($currentLine,0,15)=="         <name>") {   //Replace provider <name> according to mapping
//			$currentName = substr($currentLine,strpos($currentLine,">")+1,strpos($currentLine,"</")-15);    //old junk?
			$newLine = substr($currentLine,0,strpos($currentLine,">")+1) . $mapping[$currentProvider] . substr($currentLine,strpos($currentLine,"</"));
			fwrite($newfile,substr($currentLine,0,strpos($currentLine,">")+1) . $mapping[$currentProvider] . substr($currentLine,strpos($currentLine,"</")));
			fwrite($logfile,"The provider name was changed at line " . $lineCounter . ":\n");
			fwrite($logfile,$currentLine);
			fwrite($logfile,"was replaced with:\n");
			fwrite($logfile,$newLine . "\n");
			$providerCounter++;
			$currentProvider = $_SESSION["affiliateProviderNames"][$providerCounter];
			$providerNameSwitch = 0;
		}
		else {  //write line without modification
			fwrite($newfile,$currentLine);
		}
	}
	fwrite($logfile,"File modification completed successfully at " . date("h:i:sa") . "\n\n");
	fclose($xmlfile);
	fclose($newfile);
	fclose($logfile);
	unlink($target_XML_file); //done with intermediate file, remove it from intermediate directory.
}

/********************************************************************************
Function: modify_XML
Old version, combination of removeTopLevelSections and changeProviderNames. 
Might be faster, since it writes the final file by looping through the lines only once.
*********************************************************************************/
/*
function modify_XML($target,$new,$tags,$mapping) {
	$xmlfile = fopen($target, "r");
	$newfile = fopen($new, "w");
	$counter = 0;
	$tagCounter = 0;
	$currentTag = $tags[$tagCounter];
	$providerCounter = 0;
	$currentProvider = $_SESSION["affiliateProviderNames"][$providerCounter];
	$writingSwitch = 1;
	$providerNameSwitch = 0;
//	for($x = 0; $x <= 10000; $x++) {		//	for testing large files
//	$validClosingTag = 1;  // work on this more, not used yet.  The content of some <note> tags includes line breaks.
	while(!feof($xmlfile)) {
		$currentLine = fgets($xmlfile);
		$counter++;
		if (strpos($currentLine,$currentProvider)) {
			echo($currentLine . '<br />');
		}
		if (strpos($currentLine,$currentTag . ">") == 4) { // If the tagname begins at character number 5 (should be preceded by "   <")
			$writingSwitch = 0;
		}
		if (strpos($currentLine,"<Provider ")==6){	
			$providerNameSwitch = 1;
		}
		if ($writingSwitch == 1) {
			if ($providerNameSwitch == 1 and substr($currentLine,0,15)=="         <name>") {   //Replace provider <name> according to mapping
				$currentName = substr($currentLine,strpos($currentLine,">")+1,strpos($currentLine,"</")-15);
				fwrite($newfile,substr($currentLine,0,strpos($currentLine,">")+1) . $mapping[$currentProvider] . substr($currentLine,strpos($currentLine,"</")));
				$providerCounter++;
				$currentProvider = $_SESSION["affiliateProviderNames"][$providerCounter];
				$providerNameSwitch = 0;
			}
			else {  //write line without modification
				fwrite($newfile,$currentLine);
			}
		}
/*		//Just for testing, nothing should be written here.
		if ($writingSwitch == 0) {
			fwrite($newfile,"000" . $currentTag . $currentLine);
		}
*	/	if (strpos($currentLine,$currentTag . ">") == 5) { // If the tagname begins at character number 6 (should be preceded by "   </")
			$tagCounter++;
			$currentTag = $tags[$tagCounter];
			$writingSwitch = 1;
		}
	}
	fclose($xmlfile);
	fclose($newfile);
	echo "Your new file has been written.<br />";
}*/


?>