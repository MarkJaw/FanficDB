<?php

function mesageRead($read){ //LOL WUT ??
	if($read == 0){return " bgcolor=\"#ff5656\"";}
}

function newMessages(){
	$user_id = $_SESSION['user_id'];
	$result = mysql_query("SELECT * FROM `messages` WHERE `to_user_id`='$user_id' AND `message_read`='0'");
	$num_rows = mysql_num_rows($result);
	if($num_rows > 0){
		return "<font color=red>$num_rows</font>";
	}
	else{
		return $num_rows;
	}
}

function showInbox(){
	$wynik = mysql_query ("SELECT * FROM messages WHERE to_user_id='".$_SESSION['user_id']."';");
	echo"<div id=\"fick_main\">
	<div id=\"fick_title\"><img src=\"images/icons/email.png\"> <b>Skrzynka odbiorcza</b></div>
	<div id=\"fick_tags\">
		<table id=\"inBox\"><tr><td><b>OD</b></td><td><b>TEMAT</b></td><td><b>DATA</b></td></tr>";
	while ($rekord = mysql_fetch_assoc ($wynik)) {
		$message_id = $rekord['message_id'];
		$from_user_id = $rekord['from_user_id'];
		$message_title = $rekord['message_title'];
		$message_date = $rekord['message_date'];
		$message_read = $rekord['message_read'];
		echo "<tr id=\"chapters\" ".mesageRead($message_read)."><td>".autor_name($from_user_id)."</td><td><a href=\"?page=messages&action=message&id=$message_id\">$message_title</a></td><td>$message_date</td></tr>";
	}
	echo"</table></div></div>";
}

function showMessage(){
	$wynik = mysql_query ("SELECT * FROM messages WHERE message_id='".$_GET['id']."';");
	while ($rekord = mysql_fetch_assoc ($wynik)) {
		$from_user_id = $rekord['from_user_id'];
		$message_title = $rekord['message_title'];
		$message_content = $rekord['message_content'];
		$message_read = $rekord['message_read'];
		echo"<div id=\"fick_main\">
			<div id=\"fick_title\"><img src=\"images/icons/email.png\"> <b>$message_title</b> od ".autor_name($from_user_id)." <a href=\"?page=messages&action=delete_message&id=".$_GET['id']."\"><img src=\"images/icons/cross.png\"></a></div>
			<div id=\"fick_tags\">$message_content </div>
		</div>";
		if($message_read == "0"){	$query = mysql_query ("UPDATE messages SET  message_read='1' WHERE message_id='".$_GET['id']."';");}
	}
}

function deleteMessage(){
	$query = mysql_query ("DELETE FROM messages WHERE message_id='".$_GET['id']."';");
	showInbox();
}

function showMessages(){
	if($_GET['action'] == ""){
		showInbox();
	}
	if($_GET['action'] == "message"){
		showMessage();
	}
	if($_GET['action'] == "delete_message"){
		deleteMessage();
	}
}

?>