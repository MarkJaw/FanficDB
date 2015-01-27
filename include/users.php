<?php
function add_user($login, $mail, $password, $account_type){
	$query = "INSERT INTO users (login, mail, password, account_type) VALUES ('$login', '$mail', '$password', '$account_type');";
	$wynik = mysql_query ($query);
echo "<p><a href=\"?page=adminUsers\">User $login zosta³ dodany do bazy danych.</a></p>";
}

function rem_user($user_id){
	$wynik = mysql_query ("DELETE FROM users WHERE user_id = '$user_id';");
	echo "<p><a href=\"?page=adminUsers\">Ktos wylecia³ :P</a></p>";
}

function edit_user($user_id, $login, $mail, $password, $account_type){
	if($password == ''){
		$query = "UPDATE users SET login='$login', mail='$mail', account_type='$account_type' WHERE user_id='$user_id';";
		echo $query;
	}
	else{
		$query = "UPDATE users SET login='$login', mail='$mail', password='$password', account_type='$account_type' WHERE user_id='$user_id';";
		echo $query;
	}
	$wynik = mysql_query ($query);
	echo "<p><a href=\"?page=adminUsers\">Wpis usera $login zosta³ poprawiony.</a></p>";
}

function adminUsers(){
	if ($_SESSION['account_type'] == "0") {
			if ($_GET['co'] == ""){
				$wynik = mysql_query ("SELECT * FROM users ORDER BY user_id;");
				echo "<center><a href=\"?page=adminUsers&co=add_user\">dodaj usera</a></center>";
				echo "<table  border=\"1\" cellpadding=\"0\" cellspacing=\"1\"><thead><tr><th>ID</th><th>Login</th><th>Mail</th><th>Type</th></tr></thead><tbody>";
				while ($rekord = mysql_fetch_assoc ($wynik)) {
					$user_id = $rekord['user_id'];
					$login = $rekord['login'];
					$mail = $rekord['mail'];
					$account_type = $rekord['account_type'];
					echo "<tr><td>$user_id</td><td><a href=\"index.php?page=adminUsers&co=edit_user&user_id=$user_id\">$login</a></td><td>$mail</td><td>";
					if($account_type=='0'){
						echo 'Admin';
					} 
					if($account_type=='1'){
						echo 'Mod';
					} 
					if($account_type=='2'){
						echo 'User';
					}
					echo"</td></tr>";
				}
				echo "</tbody></table>";
		}
		else if ($_GET['co'] == "edit_user"){
			if ($_POST['action'] == '') {  // przygotowanie do poprawek
				$query = "SELECT * FROM users where user_id='".$_GET['user_id']."';";
				$wynik = mysql_query ($query);
				$rekord = mysql_fetch_assoc ($wynik);
				$user_id = $rekord['user_id'];
				$login = $rekord['login'];
				$mail = $rekord['mail'];
				$password = $rekord['password'];
				$account_type = $rekord['account_type'];

				echo "
				<FORM METHOD=\"POST\">Edycja wpisu usera o id: $user_id
				<INPUT TYPE=\"hidden\" NAME=\"page\" VALUE=\"adminUsers\">
				<INPUT TYPE=\"hidden\" NAME=\"co\" VALUE=\"edit_user\">
				<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"edit\">
				<INPUT TYPE=\"hidden\" NAME=\"user_id\" VALUE=\"$user_id\">
				<TABLE>
				<TR><TD>Login:</TD><TD><INPUT TYPE=\"text\" NAME=\"login\" VALUE=\"$login\"></TD></TR>
				<TR><TD>Mail:</TD><TD><INPUT TYPE=\"text\" NAME=\"mail\" VALUE=\"$mail\"></TD></TR>
				<TR><TD>Password:</TD><TD><INPUT TYPE=\"text\" NAME=\"password\"> :)</TD></TR>
				<TR><TD>account_type</TD><TD><INPUT TYPE=\"text\" NAME=\"account_type\" VALUE=\"$account_type\"> </TD></TR>";
				echo "</TABLE>\n";
				echo "<INPUT TYPE=\"submit\" VALUE=\"Popraw wpis\"></FORM>
				<a href=\"?page=adminUsers&co=rem_user&user_id=$user_id\"><p><b>!!! USUN USERA - AKCJA NIE DO COFNIÊCIA !!!</b></p></a><br>
				";
			}
			elseif ($_POST['action'] == 'edit') {  // poprawianie rekordu
				edit_user($_POST['user_id'], $_POST['login'], $_POST['mail'], $_POST['password'], $_POST['account_type']);
			}	
		}
		else if($_GET['co'] == "add_user"){
			if ($_GET['action'] == ""){
				echo"<table>
				<FORM METHOD=\"post\" action=\"index.php?page=adminUsers&co=add_user&action=add\">
				<TR><TD>Login:</TD><TD><INPUT TYPE=\"text\" NAME=\"login\"></TD></TR>
				<TR><TD>Mail:</TD><TD><INPUT TYPE=\"text\" NAME=\"mail\"></TD></TR>
				<TR><TD>Password:</TD><TD><INPUT TYPE=\"text\" NAME=\"password\"></TD></TR>	
				<TR><TD>account_type:</TD><TD><INPUT TYPE=\"text\" NAME=\"account_type\"></TD></TR>
				</TABLE>
				<INPUT TYPE=\"submit\" VALUE=\"Dodaj szkodnika\"></FORM>";
			} 
			elseif ($_GET['action'] == "add"){
				add_user($_POST['login'], $_POST['mail'], $_POST['password'], $_POST['account_type']);
			}
		}	
		else if($_GET['co'] == "rem_user"){
			if ($_GET['action'] == ""){
				echo "<h1> Jesteœ pewien ??</h1><br>
				<a href=\"?page=adminUsers&co=edit_user&user_id=$_GET[user_id]\"><p>NIE! ZABIRZ MNIE Z T¥D !!!</p></a><br>
				<a href=\"?page=adminUsers&co=rem_user&action=yep&user_id=$_GET[user_id]\"><p>TAK. Nie lubiê goœcia.</p></a><br>";
			} 
			elseif ($_GET['action'] == "yep"){
				rem_user($_GET['user_id']);
			}
		}
	}
	else {
		echo "CO TY TU KURWA ROBISZ ????????????????????????????????????????"; //no co :)
	}
}
?>