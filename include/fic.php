<?php

function autor_name($id) {
	$query = "SELECT login FROM users WHERE user_id = \"$id\"";
	$wynik = mysql_query($query);
		if ($row = mysql_fetch_array($wynik)) {
			$login = $row[login];
			return $login;
		} 
}
	
function fic_title($id) {
	$query = "SELECT title FROM fics WHERE fic_id = \"$id\"";
	$wynik = mysql_query($query);
		if ($row = mysql_fetch_array($wynik)) {
			$title = $row[title];
			return $title;
		} 
}
	
function fic_status($what){
	if ($what == 0){return "Prywatny.";}
	if ($what == 1){return "Publiczny.";}
	if ($what == 2){return "Oczekuje na akceptacje.";}
	if ($what == 3){return "Odrzucony przez administracje.";}
}
	
function displayChapters($fic_id, $user_id){
	$query = "SELECT * FROM chapters WHERE fic_id=\"$fic_id\"";
	$wynik = mysql_query($query);
	echo "<table id=\"chapters\">";
	while ($chapter = mysql_fetch_array($wynik)) {
		$chapter_id = $chapter[chapter_id];
		$chapter_title = $chapter[title];
		$publish_date = $chapter[publish_date];
		if($_SESSION['account_type'] < 2 & $_SESSION['logedin'] == "yes" || $_SESSION['user_id'] == $user_id){
			echo "<tr id=\"chapters\"><td><img src=\"images/icons/disk.png\"></td><td><a  href=\"?page=chapter&chapter_id=$chapter_id\">$chapter_title</a></td><td><i>$publish_date</i></td><td><a href=\"?page=editchapter&chapter_id=$chapter_id\"><img src=\"images/icons/page_edit.png\"></a></td><td><a href=\"\"><img src=\"images/icons/page_delete.png\"></a></td></tr>";
		}else{
			echo "<tr id=\"chapters\"><td><img src=\"images/icons/disk.png\"></td><td><a  href=\"?page=chapter&chapter_id=$chapter_id\">$chapter_title</a></td><td><i>$publish_date</i></td></tr>";
		}
	} 
	//if($_SESSION['account_type'] < 2 & $_SESSION['logedin'] == "yes" || $_SESSION['user_id'] == $user_id){
	//	echo "<tr><td><img src=\"images/icons/disk_multiple.png\"></td><td></td><td></td><td></td><td></td></tr>";
	//}else{
	//	echo "<tr><td><img src=\"images/icons/disk_multiple.png\"></td><td></td><td></td></tr>";
	//}
	echo "</table>";
}

function showFicCard($fic_id){
	$account_type = $_SESSION['account_type'];
	$query = "SELECT * FROM fics WHERE fic_id=\"$fic_id\"";
	$wynik = mysql_query($query);
		while ($row = mysql_fetch_array($wynik)) {
			$fic_id = $row[fic_id];
			$user_id = $row[user_id];
			$title = $row[title];
			$description = $row[description];
			$accepted = $row[accepted];
			echo "<div id=\"fick_main\"><div id=\"fick_title\"><b><a href=\"?page=showfic&fic_id=$fic_id\">$title</b></a> ";
			if($_GET['page']=="myfics"){
				echo "(".fic_status($accepted).")";
			}
			else{
				echo "by ".autor_name($user_id)."";
			}
			echo"</div>";
			if($_SESSION['account_type'] < 2 & $_SESSION['logedin'] == "yes" || $_SESSION['user_id'] == $user_id){
			echo"<div id=\"fick_mod\"><div class=\"buttons\">";
					echo "<a class=\"regular\" href=\"?page=editfic&fic_id=$fic_id\"><img src=\"images/icons/book_edit.png\">Edytuj opowiadanie</a><a class=\"positive\" href=\"?page=addchapter&fic_id=$fic_id\"><img src=\"images/icons/page_add.png\">Dodaj rozdział</a><a class=\"negative\" href=\"?page=deletefic&fic_id=$fic_id\"><img src=\"images/icons/book_delete.png\">Usuń opowiadanie</a>";
					if($accepted == "0" || $accepted == "3"){
						echo "<a class=\"positive\" href=\"?page=sendfic&fic_id=$fic_id\"><img src=\"images/icons/book_go.png\">Wyślij do akceptacji</a>";
					} 
					elseif($_SESSION['account_type'] < 2 & $accepted == "2"){
						echo "<a class=\"positive\" href=\"?page=acceptfic&fic_id=$fic_id\"><img src=\"images/icons/accept.png\">Akceptuj opowiadanie</a><a class=\"negative\" href=\"?page=rejectfic&fic_id=$fic_id\"><img src=\"images/icons/cross.png\">Odrzuć opowiadanie</a>";
					} 
			echo"</div></div>";
			}
				$story_image = "uploads/story_images/$fic_id.png";
				if (file_exists($story_image)) {
					echo "
					<div id=\"fick_image\"><img src=\"$story_image\"></div>
					<div id=\"fick_image_desc\">$description</div>
					<div id=\"fick_image_chapters\">";
					displayChapters($fic_id, $user_id);
					echo "</div><div id=\"fick_image_end\"></div>";
				} else {
					echo "
					<div id=\"fick_desc\">
						$description
					</div>";
					echo "<div id=\"fick_chapters\">";
						displayChapters($fic_id, $user_id);
					echo "</div>";
				}
			echo "<div id=\"fick_tags\">
				<div id=\"tag_ship\">Shipping</div>
			</div>
		</div>
			";
		} 
}

function showFics(){
	$limit = $_GET['limit'];
	$limit2 = $_GET['limit'] * 5;
	$query = "SELECT fic_id FROM fics WHERE status=\"1\" AND accepted=\"1\" ORDER BY fic_id DESC LIMIT $limit2, 5";
	$wynik = mysql_query($query);
	$num_rows = mysql_num_rows($wynik );
	
		while ($row = mysql_fetch_array($wynik)) {
			$fic_id = $row[fic_id];
			showFicCard($fic_id);
		} 
		
		if($limit*5 > $num_rows-5){
			$prev = $limit - 1;
			echo "<div id=\"fick_main\"><div id=\"fick_title\"><center><a href=\"?page=browse&limit=$prev\"><< poprzednia strona</a></center></div></div>";
		}
		elseif($limit > 0){
			$prev = $limit - 1;
			$next = $limit + 1;
			echo "<div id=\"fick_main\"><div id=\"fick_title\"><center><a href=\"?page=browse&limit=$prev\"><< poprzednia strona</a> | <a href=\"?page=browse&limit=$next\">następna strona >></a></center></div></div>";
		}
		else{
			echo "<div id=\"fick_main\"><div id=\"fick_title\"><center><a href=\"?page=browse&limit=1\">następna strona >></a></center></div></div>";		
		}
}

function showChapter(){
	$query = "SELECT * FROM chapters WHERE chapter_id=\"".$_GET['chapter_id']."\"";
	$wynik = mysql_query($query);
	while ($chapter = mysql_fetch_array($wynik)) {
		$chapter_title = $chapter[title];
		$fic_id = $chapter[fic_id];
		$chapter_content = $chapter[content];
		echo "<div id=\"chapter_main\"><div id=\"chapter_title\"><b>".fic_title($fic_id)."</b> / $chapter_title</div><div id=\"chapter_content\">$chapter_content</div></div>";
	} 
}



function showMyFics(){
	echo "<a href=\"?page=addfic\">Dodaj Fanficka</a><br>";
	$user_id = $_SESSION['user_id'];
	$query = "SELECT fic_id FROM fics WHERE user_id = \"$user_id\"";
	$wynik = mysql_query($query);
		while ($row = mysql_fetch_array($wynik)) {
			$fic_id = $row[fic_id];
			showFicCard($fic_id);
		}
}

function showFic(){
	$fic_id = $_GET['fic_id'];
	showFicCard($fic_id);
	echo"
			<div id=\"fick_main\">
				<div id=\"fick_title\"><b>KOMENTARZE (".commentsNumber().")</b></div>";
					showComments();
			echo "</div>";
}

function deleteFic(){
	if ($_GET['action'] == ""){
				echo "<div id=\"fick_main\"><div id=\"fick_title\">USUWANIE OPOWIADANIA</div>
				<div id=\"fick_desc\">
					<p>Jesteś pewien że chcesz to zrobić ??<p>
					<p>Ta akcja usunie to opowiadanie wraz ze wszystkimi rozdziałami.<p>
					<a href=\"?\"><p>NIE! ZABIRZ MNIE Z TĄD !!!</p></a><br>
					<a href=\"?page=deletefic&action=yep&fic_id=$_GET[fic_id]\"><p>TAK. Nie lubię tego opowiadania.</p></a>
				</div></div>";
	} 
	elseif ($_GET['action'] == "yep"){
		$fic_id = $_GET[fic_id];
		$wynik = mysql_query ("DELETE FROM fics WHERE fic_id = '$fic_id';");
		$wynik = mysql_query ("DELETE FROM chapters WHERE fic_id = '$fic_id';");
		echo "<div id=\"fick_main\"><div id=\"fick_title\">USUWANIE OPOWIADANIA</div><div id=\"fick_desc\">Opowiadanie usunięte.</div></div>";
	}
}

function showAddFic(){
	if ($_GET['action'] == ""){
		echo"<table width=100%>
		<FORM METHOD=\"post\" action=\"index.php?page=addfic&action=add\">
			<tr><TD>Tytuł</TD></tr>
			<TR><TD><INPUT TYPE=\"text\" NAME=\"title\" style=\"width:100%\"></TD></TR>
			<tr><TD>Opis</TD></tr>
			<TR><TD><textarea name=\"description\" style=\"width:100%; height:200px\"></textarea></TD></TR>
			</TABLE>
		<INPUT TYPE=\"submit\" VALUE=\"Dodaj opowiadanie\"></FORM>";
	} 
	elseif ($_GET['action'] == "add"){
		$user_id = $_SESSION['user_id'];
		$title = addslashes($_POST['title']);
		$description = addslashes($_POST['description']);
		$query = "INSERT INTO fics (user_id, title, description, status, accepted) VALUES ('$user_id', '$title', '$description', '1', '0');";
		$wynik = mysql_query ($query);
		echo "<p><a href=\"?page=myfics\">Fanfick $title został dodany.</a></p>";
	}
}

function editFic(){
			if ($_GET['action'] == '') {
				$wynik = mysql_query ("SELECT * FROM fics WHERE fic_id='".$_GET['fic_id']."';");
				while ($rekord = mysql_fetch_assoc ($wynik)) {
					$title = $rekord['title'];
					$description = $rekord['description'];
					$status = $rekord['status'];
					echo "
					<div id=\"fick_main\">
					<div id=\"fick_title\">Edycja opowiadania</div>
					<div id=\"fick_desc\">

						<FORM METHOD=\"POST\" action=\"index.php?page=editfic&action=edit\">
						<INPUT TYPE=\"hidden\" NAME=\"fic_id\" VALUE=\"".$_GET['fic_id']."\">
						<INPUT TYPE=\"hidden\" NAME=\"status\" VALUE=\"$status\">
						<p><b>Nazwa</b></p>
						<INPUT TYPE=\"text\" NAME=\"title\" VALUE=\"$title\" style=\"width:100%\">
						<p><b>Opis</b></p>
						<textarea name=\"description\" style=\"width:100%; height:350px\">$description</textarea>
						<input type=\"submit\" value=\"Save\" /> 
						</form>
					</div>
					<div id=\"fick_desc\">
					<p><b>Ilustracja</b></p>
						<FORM ENCTYPE=\"multipart/form-data\" METHOD=\"POST\" action=\"index.php?page=editfic&action=image\">
						<INPUT TYPE=\"hidden\" NAME=\"fic_id\" VALUE=\"".$_GET['fic_id']."\">
							Obrazek: <INPUT NAME=\"userfile\" TYPE=\"file\"><INPUT TYPE=\"submit\" VALUE=\"Zmień ilustracje.\">
						</form>
					</div>
				</div>
					";			
				}
			} 
			elseif ($_GET['action'] == 'edit') {
				$fic_id = $_POST['fic_id'];
				$title = $_POST['title'];
				$description = addslashes($_POST['description']);
				$status = $_POST['status'];
				$query = "UPDATE fics SET title='$title', description='$description', status='$status' WHERE fic_id='$fic_id';";
				$wynik = mysql_query ($query);
				echo "<p><a href=\"?page=myfics\">Opowiadanie zostało zaktualizowane.</a></p>";
			}
			elseif ($_GET['action'] == 'image') {
				echo "<div id=\"fick_desc\">";	
				$add="temp/".$_FILES[userfile][name];
				if(move_uploaded_file ($_FILES[userfile][tmp_name],$add)){
					echo "Poprawnie zaktualizowano ilustracje.";
					chmod("$add",0777);
				}else{
					echo "Failed to upload file Contact Site admin to fix the problem";
					echo "</div>";
					exit;
				}
					define(MAX_WIDTH, 200);
					define(MAX_HEIGHT, 200);
					$image = $add;
					$name = $_POST['fic_id'];
					$file = $add;
					$thumb = "uploads/story_images/$name.png";
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
								imagealphablending($tmp_img, true);
								imagesavealpha($tmp_img, true);								
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
						imagealphablending($img, true);
						imagesavealpha($img, true);
						imagepng($img, "$thumb", 0);
						$do = unlink("$add"); 

			echo "</div>";
			}
}

function showAddChapter(){
	if ($_GET['action'] == ""){
		$fic_id = $_GET['fic_id'];
		echo"<table width=100%>
		<FORM METHOD=\"post\" action=\"index.php?page=addchapter&action=add\">
		<INPUT TYPE=\"hidden\" NAME=\"fic_id\" VALUE=\"$fic_id\">
			<tr><TD>Tytuł rozdziału</TD></tr>
			<TR><TD><INPUT TYPE=\"text\" NAME=\"title\" style=\"width:100%\"></TD></TR>
			<tr><TD>Treść rozdziału</TD></tr>
			<TR><TD><textarea name=\"content\" style=\"width:100%; height:500px\"></textarea></TD></TR>
			</TABLE>
		<INPUT TYPE=\"submit\" VALUE=\"Dodaj rozdział\"></FORM>";
	} 
	elseif ($_GET['action'] == "add"){
		$fic_id = $_POST['fic_id'];
		$title = $_POST['title'];
		$content = addslashes($_POST['content']);
		$date = date( 'Y-m-d');
		$query = "INSERT INTO chapters (fic_id, title, content, status, publish_date) VALUES ('$fic_id', '$title', '$content', '0', '$date');";
		$wynik = mysql_query ($query);
		echo "<p><a href=\"?page=myfics\">Rozdział o tytule $title został dodany do opowiadania.</a></p>";
	}
}

function showEditChapter(){
			if ($_GET['action'] == '') {  // przygotowanie do poprawek
				$wynik = mysql_query ("SELECT * FROM chapters WHERE chapter_id='".$_GET['chapter_id']."';");
				while ($rekord = mysql_fetch_assoc ($wynik)) {
					$fic_id = $rekord['fic_id'];
					$title = $rekord['title'];
					$content = $rekord['content'];
					$status = $rekord['status'];
					echo "<table width=100% >
					<FORM METHOD=\"POST\" action=\"index.php?page=editchapter&action=edit\">
					<INPUT TYPE=\"hidden\" NAME=\"chapter_id\" VALUE=\"".$_GET['chapter_id']."\">
					<INPUT TYPE=\"hidden\" NAME=\"status\" VALUE=\"$status\">
					<tr><TD>Tytuł rozdziału</TD></tr>
					<TR><TD><INPUT TYPE=\"text\" NAME=\"title\" VALUE=\"$title\" style=\"width:100%\"></TD></TR>
					<tr><TD>Treść rozdziału</TD></tr>
					<TR><TD><textarea name=\"content\" style=\"width:100%; height:350px\">$content</textarea></TD></TR>
					</TABLE>
					<input type=\"submit\" value=\"Save\" /> 
					</form>
					";			
				}
			} 
			elseif ($_GET['action'] == 'edit') {  // poprawianie rekordu
				$chapter_id = $_POST['chapter_id'];
				$title = $_POST['title'];
				$content = addslashes($_POST['content']);
				$status = $_POST['status'];
				$query = "UPDATE chapters SET title='$title', content='$content', status='$status' WHERE chapter_id='$chapter_id';";
				$wynik = mysql_query ($query);
				echo "<p><a href=\"?page=myfics\">Rozdział o tytule $title został zaktualizowany.</a></p>";
			}
}

?>