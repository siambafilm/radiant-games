<?php
include 'main.php';

echo "
<form action='uploaduserpic.php' method='post' enctype='multipart/form-data'>
    <input type='file' name='fileToUpload' id='fileToUpload'>
    <input type='submit' value='Change avatar' name='submit'>
</form>
";
?>