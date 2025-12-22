<?php
session_start();
include 'main.php';
//$nick = $_GET['nick'];
$nick = $_SESSION['user_nick'];
$target_dir = "users/$nick/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    //echo "File is an image - " . $check["mime"] . "."; //показ
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
  echo "Sorry, only JPG, JPEG & PNG files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

    $file_pointer = "users/$nick/userpic_avatar.jpg";
    // Use unlink() function to delete a file
    if (!unlink($file_pointer)) {
        echo("Sorry, there was an error uploading your file.");
    }
    else {
        rename("users/$nick/" . htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])), $file_pointer);
        echo "Userpic has been changed"; 
    }
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}

echo "
    <form action='acc.php'>
        <input type='submit' value='Go back' name='submit'>
    </form>
    ";
?>