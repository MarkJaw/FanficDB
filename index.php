<?php session_start(); ?>
<?php 
	include('include/mysql.php'); 
	include('include/functions.php');
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Polska Baza Fanfiction</title>
	<link rel="Stylesheet" type="text/css" href="css/style.css"/> 	
	
	<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
	
	<script type="text/javascript">
		tinyMCE.init({
			mode : "textareas",
			theme : "advanced",
			theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink,|,anchor,image,cleanup,code,",

			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom"
			
		});
	</script>
	
</head>
<body>
	<div id="top">
		<div id="NAGLOWEK"><a href="?"><img src="images/baner.png"></a></div>
		<div id="MENU"><?php showMenu(); ?></div>
		<div id="TRESC">
			<?php
				if ($_GET['page'] == ""){
					showFics();
				}
				if ($_GET['page'] == "browse"){
					showFics();
				}
				if ($_GET['page'] == "chapter"){
					showChapter();
				}
				if ($_GET['page'] == "myfics"){
					showMyFics();
				}
				if ($_GET['page'] == "showfic"){
					showFic();
				}
				if ($_GET['page'] == "addfic"){
					showAddFic();
				}
				if ($_GET['page'] == "editfic"){
					editFic();
				}
				if ($_GET['page'] == "deletefic"){
					deleteFic();
				}
				if ($_GET['page'] == "sendfic"){
					sendFic();
				}
				if ($_GET['page'] == "acceptfic"){
					acceptFic();
				}
				if ($_GET['page'] == "rejectfic"){
					rejectFic();
				}
				if ($_GET['page'] == "addchapter"){
					showAddChapter();
				}
				if ($_GET['page'] == "editchapter"){
					showEditChapter();
				}
				if ($_GET['page'] == "queue"){
					showQueue();
				}
				if ($_GET['page'] == "search"){
					showSearch();
				}
				if ($_GET['page'] == "login"){
					showLogin();
				}		
				if ($_GET['page'] == "register"){
					showRegister();
				}		
				if ($_GET['page'] == "settings"){
					showSettings();
				}						
				if ($_GET['page'] == "messages"){
					showMessages();
				}			
				if ($_GET['page'] == "user"){
					showUserPage();
				}					
				if ($_GET['page'] == "adminUsers"){
					adminUsers();
				}							
				if ($_GET['page'] == "logout"){
					session_destroy(); 
					echo"<a href=\"?\">Wylogowałeś się.</a>";
				}
			?>
		</div>
		<div id="STOPKA">Marek Jaworski © <?php echo date("Y");?></div>
	</div>
</body>
</html>