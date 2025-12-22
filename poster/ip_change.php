<?php

function change($ip){
    $myfile = fopen("serv_ip.txt", "w");
    fwrite($myfile, $ip);
    fclose($myfile);
}

if($_GET['ip'] != ""){
    change($_GET['ip']);
}

?>