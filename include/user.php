<?

function user_name() {
	$id = $_SESSION['user_id'];
	$query = "SELECT login FROM users WHERE user_id = \"$id\"";
	$wynik = mysql_query($query);
		if ($row = mysql_fetch_array($wynik)) {
			$login = $row[login];
			return $login;
		} 
}

function user_acc_type($id) {
	$query = "SELECT account_type FROM users WHERE user_id = \"$id\"";
	$wynik = mysql_query($query);
		if ($row = mysql_fetch_array($wynik)) {
			$type = $row[account_type];
			if($type == "0"){return "<font color=red>Admin</font>";}
			if($type == "1"){return "<font color=blue>Moderator</font>";}
		} 
}

function user_avatar($id){
	$filename = "uploads/avatars/$id.png";
	if (file_exists($filename)) {
		return "$filename";
	} else {
		return "images/no_avatar.png";
	}
}

function showUserPage(){
$user_id = $_GET['user_id'];
echo "<div id=\"fick_main\">
	<div id=\"fick_title\"><b>".autor_name($user_id)."</b></div>
	<div id=\"fick_comment_nick\"><img src=\"".user_avatar($user_id)."\"><br>".user_acc_type($user_id)."</div>
	<div id=\"fick_comment_body\">
	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis porttitor ullamcorper massa, in eleifend elit dapibus id. Nulla in nisi urna, id tempor risus. Sed aliquam nunc eget lorem lacinia eleifend. Donec bibendum rutrum egestas. Etiam sit amet risus ac ante blandit tristique. Maecenas turpis nulla, ultricies vitae egestas sed, pharetra a mauris. Sed elementum imperdiet sem non accumsan. Quisque in sapien velit. Etiam eleifend luctus enim id scelerisque.
	Cras pulvinar sem quis erat tincidunt non luctus nulla dignissim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus pulvinar ultrices vulputate. Phasellus scelerisque facilisis malesuada. Morbi eros massa, pulvinar id pellentesque eu, adipiscing ut leo. Nunc ipsum lacus, feugiat nec pulvinar non, euismod non nunc. Etiam mollis ante at leo suscipit convallis. Curabitur ultrices eleifend nisl quis vestibulum.
	</div>
	<div id=\"fick_comment_end\"></div>
</div>";
}
?>