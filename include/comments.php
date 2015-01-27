<?php

function commentsNumber(){
	$fic_id = $_GET['fic_id'];
	$result = mysql_query("SELECT * FROM `comments` WHERE `fic_id`='$fic_id'");
	$num_rows = mysql_num_rows($result);
	return $num_rows;
}

function showAllComments(){
	$fic_id = $_GET['fic_id'];
	$query = "SELECT * FROM comments WHERE fic_id=\"$fic_id\" ORDER BY comment_id DESC";
	$wynik = mysql_query($query);
	while ($chapter = mysql_fetch_array($wynik)) {
		$user_id = $chapter[user_id];
		$comment_id = $chapter[comment_id];
		$comment = $chapter[comment];
		echo "
		<div id=\"fick_comment\">
			<div id=\"fick_comment_nick\"><img src=\"".user_avatar($user_id)."\"><br><p><b>".autor_name($user_id)."<br>".user_acc_type($user_id)."</b></p>";
				if($_SESSION['account_type'] < 2 & $_SESSION['logedin'] == "yes"){
					echo "<a href=\"?page=showfic&fic_id=$fic_id&action=deletecomment&comment_id=$comment_id\">Usuń komentarz</a>";
				}
			echo"</div>
			<div id=\"fick_comment_body\">$comment</div>
			<div id=\"fick_comment_end\"></div>
		</div>
		";
	}
	
	if($_SESSION['user_id'] != ""){
	echo "<div id=\"fick_tags\">
		<FORM METHOD=\"POST\" action=\"index.php?page=showfic&fic_id=$fic_id&action=sendcomment\">
			<tr><TD>Dodaj komentarz:</TD></tr>
			<TR><TD><textarea name=\"comment\" style=\"width:100%; height:150px\"></textarea></TD></TR>
			</TABLE>
			<input type=\"submit\" value=\"Wyślij komentarz\" /> 
		</form>
	</div>";
	}else{
		echo "<div id=\"fick_tags\">Aby dodać komentarz proszę się <a href=\"?page=login\">zalogować.</a></div>";
	}
}

function showComments(){
	if($_GET['action'] == ""){
		showAllComments(); 
	}
	if($_GET['action'] == "sendcomment"){
	
		$fic_id = $_GET['fic_id'];
		$user_id = $_SESSION['user_id'];
		$comment = $_POST['comment'];
		
		$query = "INSERT INTO comments (fic_id, user_id, comment) VALUES ('$fic_id', '$user_id', '$comment');";
		$wynik = mysql_query ($query);
		
		showAllComments(); 
	}
	if($_GET['action'] == "deletecomment"){
			$comment_id = $_GET['comment_id'];
			$wynik = mysql_query ("DELETE FROM comments WHERE comment_id = '$comment_id';");
			showAllComments(); 
	}
}
?>