<?php

/********************************************************************************
Function: startLogFile
Takes one argument. Creates a log file in the logs directory and begins logging info.
*********************************************************************************/

function startLogFile($target_file) {
	$_SESSION["log_filename"] = "logs/" . substr($target_file,0,-3) . "log";
	$logfile = fopen($_SESSION["log_filename"], "w");
	fwrite($logfile,"Currently executing script: " . $_SERVER['PHP_SELF'] . "\n");
	fwrite($logfile,"Name of host server: " . $_SERVER['SERVER_NAME'] . "\n");
	fwrite($logfile,"User IP Adress: " . $_SERVER['REMOTE_ADDR'] . "\n");
	fwrite($logfile,"Host header: " . $_SERVER['HTTP_HOST'] . "\n");
	fwrite($logfile,"HTTP Referer: " . $_SERVER['HTTP_REFERER'] . "\n");
	fwrite($logfile,"HTTP User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n");
	fwrite($logfile,"Script path: " . $_SERVER['SCRIPT_NAME'] . "\n");
}

/********************************************************************************
Function: createProviderMapping
Takes one argument, a list of affiliate provider names.
Returns an associative array mapping the list to the new names provided by the user on the previous page.
*********************************************************************************/

function createProviderMapping($old_names) {
	$providerMapping = array();
	for ($i = 0; $i < $_SESSION["numberOfOldProviders"]; $i++) {
		$providerMapping[$old_names[$i]] = $_POST[$i];
	}
	return $providerMapping;
}

?>