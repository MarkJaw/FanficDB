<?php
function showSearch(){
	if($_GET["search"] == ""){
		echo "<div id=\"fick_main\">
						<div id=\"fick_title\">Szukaj</div>
							<div id=\"fick_desc\">
								<form method=\"get\">
								<INPUT TYPE=\"hidden\" NAME=\"page\" VALUE=\"search\">
								<input type=\"text\" name=\"search\"><button type=\"submit\">Szukaj</button>
								</form>
							</div>
						</div>
					</div>";
	}
	if($_GET["search"] != ""){
		$search = $_GET["search"];
		$wynik = mysql_query("SELECT fic_id, user_id, title, description FROM fics WHERE  title LIKE \"%$search%\" AND status=\"1\" AND accepted=\"1\" OR description LIKE \"%$search%\" AND status=\"1\" AND accepted=\"1\"");
		$num_rows = mysql_num_rows($wynik);
		
		$limit = $_GET['limit'];
		$limit2 = $_GET['limit'] * 5;
		$wynik = mysql_query("SELECT fic_id, user_id, title, description FROM fics WHERE  title LIKE \"%$search%\" AND status=\"1\" AND accepted=\"1\" OR description LIKE \"%$search%\" AND status=\"1\" AND accepted=\"1\" ORDER BY fic_id DESC LIMIT $limit2, 5");
		
		if($num_rows > 0){
			echo "<div id=\"fick_main\"><div id=\"fick_title\">Wyszukuje: $search - Znaleziono $num_rows wyników :)</div></div>";
			while ($row = mysql_fetch_array($wynik)) {
			$fic_id = $row[fic_id];
			showFicCard($fic_id);
			} 	
		if($limit*5 > $num_rows-5){
			$prev = $limit - 1;
			echo "<div id=\"fick_main\"><div id=\"fick_title\"><center><a href=\"?page=serach&search=$search&imit=$prev\"><< poprzednia strona</a></center></div></div>";
		}
		elseif($limit > 0){
			$prev = $limit - 1;
			$next = $limit + 1;
			echo "<div id=\"fick_main\"><div id=\"fick_title\"><center><a href=\"?page=serach&search=$search&limit=$prev\"><< poprzednia strona</a> | <a href=\"?page=serach&search=$search&limit=$next\">następna strona >></a></center></div></div>";
		}
		else{
			echo "<div id=\"fick_main\"><div id=\"fick_title\"><center><a href=\"?page=serach&search=$search&limit=1\">następna strona >></a></center></div></div>";		
		}
		}else{
			echo "<div id=\"fick_main\"><div id=\"fick_title\">Wyszukuje: $search</div><div id=\"fick_desc\">Nic nie znaleziono :(</div></div></div>";
		}
	}
}
?>