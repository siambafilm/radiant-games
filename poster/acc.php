<?php
session_start();
include 'main.php';
?>

<body>
    <div id="ch_avatar">
        <form action="uploaduserpic.php" method="post" enctype="multipart/form-data">
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Change avatar" name="submit">
        </form>
    </div>

	<?php
        //echo "<script>reload();</script>";
        echo "<script>document.getElementById('ch_avatar').style.display = 'none';</script>";

        function profile_show($usernam){
            $rand = (string)rand(0,999);
            echo $usernam;
            echo "<br/>";
            echo "<img src='users/$usernam/userpic_avatar.jpg?$rand' width='150' height='150'><br/>";
            //echo "<script>document.getElementById('ch_avatar').style.display = 'block';</script>";

            /*echo "
            <form action='uploaduserpic.php' method='post' enctype='multipart/form-data'>
                <input type='file' name='fileToUpload' id='fileToUpload'>
                <input type='submit' value='Change avatar' name='submit'>
            </form>
            ";*/
            echo "<a href='ch_up.php'>Change userpic</a><br/>";

            echo "Your photos: <br/>";
            foreach(all_photos($usernam) as $photo){
                $rand = (string)rand(0,999);
                echo "<img src='$photo?$rand' width='500'><br/>";
                echo "<br/>";
            }
        }

        function all_photos($user){
            $dir = "users/$user/photos";
			$files = glob($dir . '/*.*');
            return $files;
        }

        if($_SESSION['user_nick'] == NULL){
            echo "<script>window.location = 'lar_acc.php';</script>";
        }        
        profile_show((string)$_SESSION['user_nick']);
        echo "<button onclick='myFunction()'>Reload page</button>
        <script>
        function myFunction() {
            window.location.reload(true); 
            window.scrollTo(0, 0);
        }
        </script>";
	?>
</html>