<?php
$title = "Welcome";
include "header.php";?>

<form action="upload_and_read.php" method="post" enctype="multipart/form-data">
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload"><br><br>
    <input type="submit" value="Upload File and proceed to next page" name="submit">
</form>

<?php
include "footer.php";
?>