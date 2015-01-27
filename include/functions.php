<?php

include('user.php'); //funkcje i strona usera
include('fic.php'); //wyswietlanie opowiadan
include('users.php'); //edycja userow
include('messages.php'); //system wiadomosic prywatnych
include('settings.php'); //panel ustawien
include('comments.php'); //system komentarzy
include('queue.php'); //system kolejki opowiadan
include('search.php'); //wyszukiwarka opowiadan

function showMenu(){
	if ($_SESSION['user_id'] == "") {
		echo "
		<ul>
			<li><a href=\"?page=browse\">Lista opowiadań</a></li>
			<li><a href=\"?page=search\">Szukaj opowiadania</a></li>
			
			<li><a href=\"?page=register\">Rejestracja</a></li>
			<li><a href=\"?page=login\">Zaloguj</a></li>
		</ul>";
	}
	else{
		if ($_SESSION['account_type'] == "0"){ //admin
			echo "
			<ul>
			<li><a href=\"?page=browse\">Lista opowiadań</a></li>
			<li><a href=\"?page=search\">Szukaj opowiadania</a></li>
				
				<li><a href=\"?page=queue\">Kolejka opowiadan (".ficsInQueue().")</a></li>
				<li><a href=\"?\">Zgłoszenia</a></li>
				
				<li><a href=\"?page=myfics\">Moje opowiadania</a></li>
				<li><a href=\"?page=messages\">Wiadomości (".newMessages().")</a></li>
				<li><a href=\"?page=settings\">Ustawienia</a></li>
				
				<li><a href=\"?page=adminUsers\">Użytkownicy</a></li>
				
				<li><a href=\"?page=logout\">Wyloguj ".user_name()."</a></li>
			</ul>";
		}
		if ($_SESSION['account_type'] == "1"){ //mod
			echo "
			<ul>
			<li><a href=\"?page=browse\">Lista opowiadań</a></li>
			<li><a href=\"?page=search\">Szukaj opowiadania</a></li>
				
				<li><a href=\"?page=queue\">Kolejka opowiadan (".ficsInQueue().")</a></li>
				<li><a href=\"?\">Zgłoszenia</a></li>
				
				<li><a href=\"?page=myfics\">Moje opowiadania</a></li>
				<li><a href=\"?page=messages\">Wiadomości (".newMessages().")</a></li>
				<li><a href=\"?page=settings\">Ustawienia</a></li>
				
				<li><a href=\"?page=logout\">Wyloguj ".user_name()."</a></li>
			</ul>";
		}
		if ($_SESSION['account_type'] == "2"){ //user
			echo "
			<ul>
			<li><a href=\"?page=browse\">Lista opowiadań</a></li>
			<li><a href=\"?page=search\">Szukaj opowiadania</a></li>
				
				<li><a href=\"?page=myfics\">Moje opowiadania</a></li>
				<li><a href=\"?page=messages\">Wiadomości (".newMessages().")</a></li>
				<li><a href=\"?page=settings\">Ustawienia</a></li>
				
				<li><a href=\"?page=logout\">Wyloguj ".user_name()."</a></li>
			</ul>";
		}
	}
}

function showLogin(){
		if($_POST['login'] && $_POST['password']) {
			$login = addslashes($_POST['login']);
			$password = addslashes($_POST['password']);
			$query = "SELECT user_id, account_type FROM users WHERE login = \"$login\" AND password = \"$password\"";
			$wynik = mysql_query($query);
				if ($row = mysql_fetch_array($wynik)) {
					$_SESSION['user_id'] = $row['user_id'];
					$_SESSION['logedin'] = "yes";
					$_SESSION['account_type'] = $row['account_type'];
					echo "<center>Zostałeś zalogowany.<br><a href=\"?\">Dalej</a></center>";
				} 
				else {
					echo "<center>Błąd logowania! <a href=\"?page=login\">Sprobuj ponownie!</a></center>";
				}
		} 
		else {
			echo'
			<div id="stylized" class="form">
				<form id="form" name="form" method="post" action="?page=login">
				<label>Login</label><input type="text" name="login" id="name" /><br>
				<label>Hasło</label><input type="password" name="password" id="password" /><br>
				<button type="submit">Zaloguj</button>
				</form>
			</div>';
			}
}

function showRegister(){

	if($_POST[name] != "" & $_POST[email] != "" & $_POST[password] != ""){
	
		$login = $_POST[name];
		$mail = $_POST[email];
		$password = $_POST[password];
		$account_type = "2";
	
		$loguj="select login from users where login='$login'"; 
		$rekordy = mysql_query($loguj);
		if(mysql_num_rows($rekordy)==0)
		{
			$query = "INSERT INTO users (login, mail, password, account_type) VALUES ('$login', '$mail', '$password', '$account_type');";
			$wynik = mysql_query ($query);
			echo "<p><a href=\"?page=login\">Dziękuje za zarejestrowanie.<br>Już możesz sie zalogować używając danych które podałeś wcześniej.</a></p>";
		}
		else
		{
			echo "<p><a href=\"?page=register\">Ten login jest już zajęty :(<br>Spróbuj ponownie z innym loginem.</a></p>";
		}
	}
	else{
		echo'
		<div id="fick_main">
			<div id="fick_title">Rejestracja</div>
				<div id=\"fick_desc\">
					<div id="stylized" class="form">
						<form id="form" name="form" method="post" action="index.php?page=register">

						<label>Login</label><input type="text" name="name" id="name" />

						<label>Email</label>
						<input type="text" name="email" id="email" />

						<label>Hasło</label>
						<input type="password" name="password" id="password" />

						<label>Powtórz hasło</label>
						<input type="password" name="password2" id="password" />
						
						<button type="submit">Zarejestruj</button>
						<div class="spacer"></div>

						</form>
					</div>
				</div>
			</div>
		</div>';
	}
}
?>