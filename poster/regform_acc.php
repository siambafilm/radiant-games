<?php
session_start();
include 'main.php';
?>
<body>
    <div id="reg_input">
        <form method="post">
            Registration:
            <br/>
            Nick: <input type="text" name="reg_nickname_text" id="reg_nickname_text">
            <br/>
            Password: <input type="text" name="reg_password_text" id="reg_password_text">
            <br/>
            <input type='submit' name='regg_btn' value='Register'/>
        </form>
    </div>

    <?php
        function registr($nick, $pass){
            $users = array_filter(glob('users/*'), 'is_dir');
            if(in_array("users/$nick", $users)){
                echo "<script>alert('This nic was already taken by other user\nSome numbers will be added to your nick')</script>";
                $nick = $nick . (string)rand(0,999);
            }
            try{
                mkdir("users/$nick");
                mkdir("users/$nick/photos");
                mkdir("users/$nick/descs");
                $myfile = fopen("users/$nick/password.txt", "w");
                fwrite($myfile, $pass);
                fclose($myfile);
                copy("default_userpic.png", "users/$nick/userpic_avatar.jpg");
                $_SESSION["user_nick"] = $nick;
                echo "<script>window.location = 'acc.php';</script>";
            }catch(Exception $ex){
                echo "<script>alert('Problems on server\nError: $ex')</script>";
            }
        }
        
        if(isset($_POST['regg_btn'])) {
            registr($_POST['reg_nickname_text'], $_POST['reg_password_text']);
        }
    ?>
</body>