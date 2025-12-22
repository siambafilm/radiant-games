<?php
session_start();
include 'main.php';
?>
<body>
    <div id="login_input">
        <form method="post">
            Login:
            <br/>
            Nick: <input type="text" name="nickname_text" id="nickname_text">
            <br/>
            Password: <input type="text" name="password_text" id="password_text">
            <br/>
            <input type='submit' name='loginin_btn' value='Login'/>
        </form>
    </div>

    <?php
    function login_check($nick, $pass){
        $users = array_filter(glob('users/*'), 'is_dir');
        if(in_array("users/$nick", $users)){
            try{
                $myfile = fopen("users/$nick/password.txt", "r");
                if($pass == fread($myfile,filesize("users/$nick/password.txt"))){
                    $_SESSION["user_nick"] = $nick;
                    echo "<script>window.location = 'upload_photo.php';</script>";
                }
                else{
                    echo "<script>alert('Password isn`t correct')</script>";
                }
                fclose($myfile);
            }catch(Exception $ex){
                echo "<script>alert('Problems on server\nError: $ex')</script>";
            }
        }
        else{
            echo "<script>alert('Can`t find user $nick')</script>";
        }
    }

    if(isset($_POST['loginin_btn'])) {
        login_check($_POST['nickname_text'], $_POST['password_text']);
    }
    ?>
</body>