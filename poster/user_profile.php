<?php
session_start();
include 'main.php';

function all_photos($user){
    $dir = "users/$user/photos";
    $files = glob($dir . '/*.*');
    return $files;
}

function profile_show($usernam){
    echo "<a href='lenta.php'>Back</a><br/><br/>";
    $rand = (string)rand(0,999);
    echo "$usernam<br/>";
    echo "<img src='users/$usernam/userpic_avatar.jpg?$rand' width='150' height='150'><br/>";
    echo "Photos: <br/>";
    foreach(all_photos($usernam) as $photo){
        $rand = (string)rand(0,999);
        echo "<img src='$photo?$rand' width='500'><br/>";
        echo "<br/>";
    }
}

if($_GET['user'] != NULL) profile_show($_GET['user']);
else echo "Error";
?>