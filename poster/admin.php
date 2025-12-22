<?php
session_start();
include 'main.php';
?>
<body>
    <div id="login_input">
        <form method="post">
            Admin panel login:
            <br/>
            Admin password: <input type="text" name="password_text" id="password_text">
            <br/>
            <input type='submit' name='loginin_btn' value='Login'/>
        </form>
    </div>

    <?php
    function login_check($pass){
        if($pass == "_p@ssw0rd_"){
            $dirs = array_filter(glob('users/*'), 'is_dir');
            echo "<br/>";
            echo "<br/>";
            foreach($dirs as $value){
                $dir = "$value/photos";
		        $phs = glob($dir . '/*.*');
                $cnt = count($phs);
                $myfile = fopen("$value/password.txt", "r");
                $passw = fread($myfile,filesize("$value/password.txt"));
                echo "Nick: " . str_replace("users/", "", $value) . " | Photos: " . $cnt . " | Password: " . $passw;
                echo "<br/>";
                fclose($myfile);
            }
            
        }
    }

    if(isset($_POST['loginin_btn'])) {
		login_check($_POST['password_text']);
    }
    ?>
</body>