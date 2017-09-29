<?php

/********************************************************************************
Function: getTopLevelTags
Takes a target filename. Returns an array of XML tags that were preceded by exactly 1 indent (3 spaces).
Used to produce the list that is displayed for user input.
*********************************************************************************/
function getTopLevelTags($target) {
	$xmlfile = fopen($target, "r");
	$topLevelTags = array();
//	for($x = 0; $x <= 10000; $x++) {  			// for testing the first part of large files
	while(!feof($xmlfile)) {
		$currentLine = fgets($xmlfile);
		if (strpos($currentLine,"<")==3){		//could be generalized to deal with lower levels, since indents are multiples of 3 spaces
			$xmltag = substr($currentLine,strpos($currentLine,"<")+1,strpos($currentLine,">")-4);
			if (substr($xmltag,0,1)!="/") {		//exclude closing XML tags
				array_push($topLevelTags,$xmltag);
			}
		}
	}
	fclose($xmlfile);
	return $topLevelTags;
}

/********************************************************************************
Function: getOldProviderNames
Takes a target filename. Reads the XML file and returns an array of provider names from the <name> tag inside each <provider> tag.
Used to produce the list of affiliate provider names.
*********************************************************************************/

function getOldProviderNames($target) {
	$xmlfile = fopen($target, "r");
	$providerNames = array();
	$readname = 0;								//only read names inside provider tags
//	for($x = 0; $x <= 10000; $x++) {  			// for testing the first part of large files
	while(!feof($xmlfile)) {
		$currentLine = fgets($xmlfile);
		if (strpos($currentLine,"<Provider ")==6){	
			$readname = 1;
		}
		if ($readname == 1 and substr($currentLine,0,15)=="         <name>") {
			$providerName = substr($currentLine,strpos($currentLine,">")+1,strpos($currentLine,"</")-15);
			array_push($providerNames,$providerName);
			$readname = 0;
		}
	}
	fclose($xmlfile);
	return $providerNames;
}

/********************************************************************************
Function: getNewProviderNames
Reads a hard-coded txt file provided by Anne and returns an array of provider names. 
Used to produce the list of VOANational provider names.
*********************************************************************************/

function getNewProviderNames() {
	$txtfile = fopen("MA_Providers_NL.txt", "r");
	$providerNames = array();
	while(!feof($txtfile)) {
		$currentLine = fgets($txtfile);
		$providerName = trim($currentLine);
		array_push($providerNames,$providerName);
	}
	fclose($txtfile);
	return $providerNames;
}


/********************************************************************************
Function: getAnyLevelTags
Work in progress.
*********************************************************************************/
/*
function getAnyLevelTags($target,$level) {
	$xmlfile = fopen($target, "r");
//	$counter = 0;			//not necessary
	$validClosingTag = 1;		// Fix this still
	$topLevelTags = array();
//	for($x = 0; $x <= 10000; $x++) {  			// for testing large files
	while(!feof($xmlfile)) {
		$currentLine = fgets($xmlfile);
//		$counter++;			// not necessary
		if (strpos($currentLine,"<")==3*$level){	//could be generalized to deal with lower levels, since indents are multiples of 3 spaces
			$xmltag = substr($currentLine,strpos($currentLine,"<")+1,strpos($currentLine,">")-1-3*$level);
			if (substr($xmltag,0,1)!="/") {		//exclude closing XML tags
				if (!in_array($xmltag,$topLevelTags)) {
					array_push($topLevelTags,$xmltag);
				}
			}
		}
	}
	fclose($xmlfile);
	return $topLevelTags;
}*/


?>