<?php
session_start();
include 'main.php';

if($_SESSION['user_nick'] == NULL){
    echo "<script>window.location = 'lar.php';</script>";
}

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