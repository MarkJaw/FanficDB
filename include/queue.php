<?

function ficsInQueue(){
	$result = mysql_query("SELECT * FROM fics_queue");
	$num_rows = mysql_num_rows($result);
	if($num_rows > 0){
		return "<font color=red>$num_rows</font>";
	}
	else{
		return $num_rows;
	}
}

function sendFic(){
	if ($_GET['action'] == '') {
		$fic_id = $_GET['fic_id'];
		
		echo"<div id=\"fick_main\">
					<div id=\"fick_title\">Zgłoszenie do akceptacji.</div>
					<div id=\"fick_desc\"><center>
		
		<p><h1>Uwaga</h1></p>
		<p>Przed wysłaniem opowiadania do akceptacji upewnij się że:</p>
		<p>- Napisałeś opis który zgadza się z treścią opowiadania.</p>
		<p>- Twoje opowiadanie jest odpowiednio otagowane.</p>
		<p>- Nie łamiesz <b>ŻADNEGO</b> punktu regulaminu tym co wysyłasz.</p>
		<br>
		<p>Jeśli jesteś pewien że spełniasz powysze warunki, <a href=\"?page=sendfic&fic_id=$fic_id&action=send\">naciśnij tutaj.</a></p>
		
		</center></div></div>";
	} 
	elseif ($_GET['action'] == 'send') {
		$fic_id = $_GET['fic_id'];
		$user_id = $_SESSION['user_id'];
		
		$query = "INSERT INTO fics_queue (fic_id, user_id) VALUES ('$fic_id', '$user_id');";
		$wynik = mysql_query ($query);
		
		$query = "UPDATE fics SET  accepted='2' WHERE fic_id='$fic_id';";
		$wynik = mysql_query ($query);
		
		echo "<p><a href=\"?page=myfics\">Zgłoszenie zostało wysłane. Administracja zajmie się twoim opowiadaniem gdy tylko będzie miała czas :)</a></p>";
		
	}
}

function showQueue(){	
	$wynik = mysql_query ("SELECT * FROM fics_queue");
			echo"<div id=\"fick_main\">
				<div id=\"fick_title\"><b>Czyściec na fanficki</b></div>
				<div id=\"fick_desc\">";
		while ($rekord = mysql_fetch_assoc ($wynik)) {
			$id = $rekord['id'];
			$fic_id = $rekord['fic_id'];
			$user_id = $rekord['user_id'];
			echo "<a href=\"?page=showfic&fic_id=$fic_id\">$id - ".fic_title($fic_id)." by ".autor_name($user_id)."</a><br>";
		}
		echo"</div>
			</div>";
}

function acceptFic(){
	
		$fic_id = $_GET['fic_id'];
		
		$query = "DELETE FROM fics_queue WHERE fic_id = '$fic_id';";
		$wynik = mysql_query ($query);
		
		$query = "UPDATE fics SET  accepted='1' WHERE fic_id='$fic_id';";
		$wynik = mysql_query ($query);
		
		echo "<p><a href=\"?page=queue\">Opowiadanie zakceptowane. Powrót do kolejki.</a></p>";
}

function rejectFic(){
	if ($_GET['action'] == '') {
		$fic_id = $_GET['fic_id'];
					echo "<table width=100% >
					<FORM METHOD=\"POST\" action=\"index.php?page=rejectfic&action=send\">
					<INPUT TYPE=\"hidden\" NAME=\"fic_id\" VALUE=\"".$_GET['fic_id']."\">
					<tr><TD>Powód odrzucenia opowiadania.</TD></tr>
					<TR><TD><textarea name=\"description\" style=\"width:100%; height:350px\">$description</textarea></TD></TR>
					</TABLE>
					<input type=\"submit\" value=\"Save\" /> 
					</form>
					";		
	}
	if ($_GET['action'] == 'send') {
		
		$powod = addslashes($_POST['description']);
		$fic_id = $_POST['fic_id'];
		$date = date( 'Y-m-d H:i:s');
		
		//usuwamy opowiadanie z kolejki
		$query = mysql_query ("DELETE FROM fics_queue WHERE fic_id = '$fic_id';");

		//zmieniamy status na odrzucone
		$query = mysql_query ("UPDATE fics SET  accepted='3' WHERE fic_id='$fic_id';");
		
		//pobieramy informacje do wiadomosci o powodzie odrzucenia
		$wynik = mysql_query("SELECT * FROM fics WHERE fic_id = \"$fic_id\"");
		while ($row = mysql_fetch_array($wynik)) {
			$user_id = $row[user_id];
			$title = $row[title];
			//wysylamy wiadomosc o odrzuceniu
			$query = "INSERT INTO messages (from_user_id, to_user_id, message_title, message_content, message_date) VALUES (2, '$user_id', 'Twoje opowiadanie o tytule \"$title\" zostało odrzucone.', '$powod', '$date');";
			$wynik = mysql_query ($query);
		}	
			
		
		echo "<p><a href=\"?page=queue\">Opowiadanie odrzucone. Powrót do kolejki.</a></p>";
	}	
}
?>