VOA Data Conversion Toolkit 
Version Beta 1.0
Published 9/29/2017
Requirements:
	Web server running php, minimum - PHP Version 5.6.30
	Browser that supports the 'download' attribute of the <a> tag. (Firefox, Chrome, possibly others.)

Description:
	Used for post-processing of Bowman XML export files.
	The user can select which top-level tags should be removed from the final version, and can rename the affiliate providers.

Contents:
	files:
		XML_modification.php - Landing page. User chooses an XML file to upload.
		upload_and_read.php - Reads the uploaded file and provides the user with:
								1. a list of top level tags which can be selected for removal.
								2. a list of provider names and a dropdown menu for each, so that the user can configure the provider mapping.
									Currently the contents of the dropdown menu is hardcoded with voama names.
		write_new_file.php - Produces a new XML file according to the user's instructions. Contains links to download the new file and the log file.
		header.php
		footer.php
		index.php
		MA_Providers_NL.txt - list of national names for MA providers.
		readme.txt
		
	directories:
		php-libraries - contains 4 php files with various functions.
								file_reading.php
								file_modification.php
								upload.php
								utilities.php
		uploads - stores uploaded files. Files deleted when complete.
		intermediate_files - for internal use. Files deleted when complete.
		new_files - stores files to be downloaded by user. Files are not automatically deleted upon download.
		logs - stores log files. Files are not automatically deleted upon download.
		images - contains logos
		
Authorship:
	David Atkinson, AxioTek Ltd., axiotek.com
