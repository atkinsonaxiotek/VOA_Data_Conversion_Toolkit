<?php

/********************************************************************************
Function: upload
Takes one argument. Uploads the file. Does not upload in any of these cases:
	1. A file with that name already exists in the uploads folder on the server.
	2. The file is larger than some limit.
	3. The file is not of a certain type.
*********************************************************************************/

function upload($target_file) {
	$uploadOk = 1;
	$fileType = pathinfo($target_file,PATHINFO_EXTENSION);

	// Check if file already exists
	if (file_exists($target_file)) {
		echo 'The file "'. basename( $_FILES["fileToUpload"]["name"]). '" already exists!<br />';
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 50000000) { // 50 MB limit. Arbitrary, can be changed.
		echo "Your file is too large.<br />";
		$uploadOk = 0;
	}
	// Allow only certain file formats
	if($fileType != "xml" && $fileType != "csv") {
		echo "Only XML and CSV files are allowed.<br />";
		$uploadOk = 0;
	}
	// Check if $uploadOk has been set to 0
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.<br /><br />";
	} else {  // try to upload file
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			echo 'The file "'. basename( $_FILES["fileToUpload"]["name"]). '" has been uploaded.<br /><br />';
		} else {
			echo "Sorry, there was an error uploading your file.<br /><br />";
		}
	}
}

?>