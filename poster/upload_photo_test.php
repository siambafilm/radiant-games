<?php
session_start();
include 'main.php';

/*if($_SESSION['user_nick'] == NULL){
    echo "<script>window.location = 'lar.php';</script>";
}*/

echo "
        <form action='upload.php' method='post' enctype='multipart/form-data'>
            <input type='file' name='fileToUpload' id='fileToUpload'>
            <br/>
            Description: <input type='text' name='desc_text' id='desc_text'>
            <br/>
            <input type='submit' value='Upload photo' name='submit'>
        </form>
        ";
?>
<form action="<?php echo $_server['php-self'];  ?>" method="post" enctype="multipart/form-data" id="something" class="uniForm">
    <input name="new_image" id="new_image" size="30" type="file" class="fileUpload" />
    <button name="submit" type="submit" class="submitButton">Upload/Resize Image</button>
<form>