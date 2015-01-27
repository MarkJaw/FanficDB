<?php

function showSettings(){
	echo"<div id=\"fick_main\">
	<div id=\"fick_title\">USTAWIENIA</div>";
	
		if($_GET['action'] == ""){
			showSettingsForm();
		}
		if($_GET['action'] == "avatar"){
			saveAvatar();
			showSettingsForm();
		}
		if($_GET['action'] == "resetavatar"){
			resetAvatar();
			showSettingsForm();
		}
	echo"</div>";
}

function showSettingsForm(){
	$user_id = $_SESSION['user_id'];
	echo"
			<div id=\"fick_desc\">
				<table>
					<p><b>Hasło i adres kontaktowy</b></p>
					<FORM METHOD=\"post\" action=\"\">
					<tr><td>Stare hasło</td><TD><INPUT TYPE=\"text\" NAME=\"town_name\"></TD></tr>
					
					<tr><td>Nowe hasło</td><TD><INPUT TYPE=\"text\" NAME=\"town_owner\"></TD></tr>
					<tr><td>Potwierdź nowe hasło</td><TD><INPUT TYPE=\"text\" NAME=\"town_chunks\"></TD></tr>
					<tr><td>Mail</td><TD><INPUT TYPE=\"text\" NAME=\"town_status\"></TD></tr>
					<tr><td colspan=2 ><INPUT TYPE=\"submit\" VALUE=\"Zastosuj\"></FORM></tr>
				</table>
			</div>
			<div id=\"fick_desc\">
				<p><b>Avatar</b></p>
				<FORM ENCTYPE=\"multipart/form-data\" ACTION=\"?page=settings&action=avatar\" METHOD=POST>
				Wybież plik: <INPUT NAME=\"userfile\" TYPE=\"file\">
				<INPUT TYPE=\"submit\" VALUE=\"Zmień avatar.\"></FORM>
				System najlepiej radzi sobie z kwadratowymi plikami wiec starajcie się takie wrzucać :P
				<p><b>Aktualny avatar:</b></p>
				<img src=\"".user_avatar($user_id)."\"><br>
				<a href=\"?page=settings&action=resetavatar\">Usuń avatar</a>
			</div>
			<div id=\"fick_tags\">
				<p><b>Ustawienia wyświetlania opowiadań</b></p>
				<FORM METHOD=\"POST\">
					<TABLE>
							<tr><td>Wyświetlanie opowiadań violence/grimdark:</td><TD>
								<select name=\"what\">
									<option value=\"1\">Nie</option> 
									<option value=\"2\">Tak</option>
								</select></TD></tr>
							<tr><td>Wyświetlanie opowiadań erotycznych:</td><TD>
								<select name=\"what\">
									<option value=\"1\">Nie</option> 
									<option value=\"2\">Tak</option>
								</select></TD></tr>
					</TABLE>
				<INPUT TYPE=\"submit\" VALUE=\"Zastosuj\"></FORM>
			</div>
	";
}

function saveAvatar(){
	echo "<div id=\"fick_desc\">";	
				$add="temp/".$_FILES[userfile][name];
				if(move_uploaded_file ($_FILES[userfile][tmp_name],$add)){
				echo "Poprawnie zaktualizowano avatar.";
				
				chmod("$add",0777);
				
				}else{
					echo "Failed to upload file Contact Site admin to fix the problem";
					echo "</div>";
					showSettingsForm();
					exit;
				}
					define(MAX_WIDTH, 100);
					define(MAX_HEIGHT, 100);
					$image = $add;
					$name = $_SESSION['user_id'];
					$file = $add;
					$thumb = "uploads/avatars/$name.png";
						$img = null;
						$ext = strtolower(end(explode('.', $file)));
						if ($ext == 'jpg' || $ext == 'jpeg') {
							$img = @imagecreatefromjpeg($file);
						} else if ($ext == 'png') {
							$img = @imagecreatefrompng($file);
						} else if ($ext == 'gif') {
							$img = @imagecreatefrompng($file);
						}
						if ($img) {

							$width = imagesx($img);
							$height = imagesy($img);
							$scale = min(MAX_WIDTH/$width, MAX_HEIGHT/$height);

							if ($scale < 1) {
								$new_width = floor($scale*$width);
								$new_height = floor($scale*$height);
								$tmp_img = imagecreatetruecolor($new_width, $new_height);		
								
								imagealphablending($tmp_img, false);
								imagesavealpha($tmp_img, true);

								$trans_layer_overlay = imagecolorallocatealpha($tmp_img, 220, 220, 220, 127);
								imagefill($tmp_img, 0, 0, $trans_layer_overlay);
								
								imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
								imagedestroy($img);
								$img = $tmp_img;
							}
						}

						if (!$img) {
							$img = imagecreate(MAX_WIDTH, MAX_HEIGHT);
							imagecolorallocate($img,0,0,0);
							$c = imagecolorallocate($img,70,70,70);
							imageline($img,0,0,MAX_WIDTH,MAX_HEIGHT,$c2);
							imageline($img,MAX_WIDTH,0,0,MAX_HEIGHT,$c2);
						}
					//	imagealphablending($img, true);
						imagesavealpha($img, true);
						imagepng($img, "$thumb", 0);
						$do = unlink("$add"); 

			echo "</div>";
}

function resetAvatar(){
	$user_id = $_SESSION['user_id'];
	$avatar = "uploads/avatars/$user_id.png";
	$do = unlink("$avatar"); 
	echo "<div id=\"fick_desc\">";	
		echo "Poprawnie usunięto avatar.";
	echo "</div>";
}
?>