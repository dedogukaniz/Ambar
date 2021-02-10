<?php

$file = file_get_contents("task.json");
$json_a = json_decode($file, true);
$closed=0;
$havetasks = 0;
?>
<!DOCTYPE html>
<html><head>
<title>Yapılacaklar listesi</title>
<meta charset="UTF-8">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" src="kickstart.js"></script>                                                   
<link rel="stylesheet" type="text/css" href="style.css" media="all" />                          
</head><body><div id="wrap">
<div>

	<?php
	echo "<h1>&nbsp;Yapılacaklar Listesi</h1>";
	echo "<form name=\"edit\" action=\"action.php\" method=\"GET\">";
	echo "<input name=\"content\" class=\"İnputAlani\" type=\"text\" placeholder=\"Yeni Görev Ekle\" ></input>";
	echo "<input type=\"hidden\" name=\"action\" value=\"add\"></input>";
	echo "&nbsp; &nbsp; ";
	echo "<input type=\"submit\" class=\"MaviButon\" name=\"submit\" value=\"Görev Ekle\"></input>";
	echo "</form>";

	echo "<ul>";
	if(is_array($json_a)) {		
		foreach ($json_a as $item => $task) {
			if ($task['status'] == "open") {	
				$havetasks=1;
				echo "<li>";
				echo $task['task'];
				echo "[ <a href=\"action.php?id=" .$item. "&action=done\"><span class=\"icon small darkgray\" data-icon=\"Yapıldı Listesine Aktar\"></span></a> ]";
				echo " [ <a href=\"action.php?id=" .$item. "&action=edit\"><span class=\"icon small darkgray\" data-icon=\"Güncelle\"></span></a> ]";
				echo "[ <a href=\"action.php?id=" .$item. "&action=delete\"><span class=\"icon small darkgray\" data-icon=\"Sil\"></span></a> ] ";
				echo "</li>";
			}	
		}
		if($havetasks == 0) {
			echo "Görev Yok.";	
		}
	} else { 
		echo"Önce Görev Ekleyiniz.";	
	}
	echo "</ul>";

	echo"<h1>&nbsp;Yapılan Görevler</h1>";
	echo "<ul>";
		if(is_array($json_a)) {	
		foreach ($json_a as $item => $task) {
			if ($task['status'] == "closed") {
			$closed=1;
			echo "<li>";
			echo $task['task'];
			echo " [ <a class=\"iconsmalldarkgray\" href=\"action.php?id=" .$item. "&action=delete\"><span class=\"icon small darkgray\" data-icon=\"Sil\"></span></a> ] ";		
			echo "</li>";
			}	
		}

		if ($closed == 0) {
			echo"Lütfen önce bir görevi bitirin.";
		}
	} else { 
		echo"Görev yok. Lütfen önce bir tane ekleyin.";	
	}		
	echo "</ul>";
	?>
</div>

<div>
	
</div> 

</div>

</div>
</body></html>
