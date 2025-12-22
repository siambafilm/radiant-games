<?php
try{
	include 'main.php';

	function random_user(){
		$dirs = array_filter(glob('users/*'), 'is_dir');
		#print_r( $dirs);
		$dirsr = $dirs[array_rand($dirs)];
		return str_replace("users/", "", $dirsr);
	}
	function random_photo($user){
		$dir = "users/$user/photos";
		$files = glob($dir . '/*.*');
		$file = array_rand($files);
		#return $files[$file];
		return str_replace("users/$user/photos/", "", $files[$file]);
	}
	
	$photos = array();

	$i = 40;

	while($i != 0){
		try{
			$user = random_user();
			$ph = random_photo($user);
			$userpic = "users/$user/userpic_avatar.jpg";
			$photo = "users/$user/photos/$ph";
			$desc = "users/$user/descs/$ph.txt";
			
			if($ph != NULL){
				$photos[] = $ph;
				
				echo "
				<table>
					<tr>
						<td><img src='$userpic' width='64' height='64'></td>
						<td><a href='user_profile.php?user=$user'>$user</a></td>
					</tr>
				</table>

				<img src='$photo' width='390'>
				";

				if(file_exists($desc)){
					$myfile = fopen($desc, "r");
					echo "<br/>" . fread($myfile,filesize($desc));
					fclose($myfile);
				}

				echo "<br/>";
				$i -= 1;
			}
			/*if(in_array($ph, $photos)){
			}
			else{*/
			//}
		}
		catch(Exception $e){
			//echo $e;
		}
	}

	//echo "<input type='button' value='Reload Page' onClick=''";
	//echo "</script>";
	echo "<button onclick='myFunction()'>Reload page</button>
	<script>
      function myFunction() {
        window.location.reload(true); 
		window.scrollTo(0, 0);
      }
    </script>";

	
}
catch(Exception $e){
	echo $e;
}
?>