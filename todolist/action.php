<?php
$file = "task.json";
$jsonfile = file_get_contents($file);
$json_a = json_decode($jsonfile, true);

if (empty($_GET['action'])) {
	echo"<a href=\"index.php\">Lütfen Tekrar Deneyiniz</a>";
} elseif (isset($_GET['action']) && $_GET['action'] == 'edit' ) {

	$taskid=htmlspecialchars($_GET['id']);
	$found=0;
	echo "<h2>Güncelle</h2>";
	foreach ($json_a as $item => $task) {
		if ($item == $taskid) {
		$found = 1;
		echo "<form name=\"edit\" action=\"action.php\" method=\"GET\">";
		echo "<input name=\"content\" type=\"text\" value=\"";
		echo $task['task'];
		echo "\"></input>";
		echo "<input type=\"hidden\" name=\"id\" value=\"".  $taskid ."\"></input>";		
		echo "<input type=\"hidden\" name=\"action\" value=\"update\"></input>";
		echo "<input type=\"submit\" name=\"submit\" value=\"Submit\"></input>";
		echo "</form>";
		echo "<p />";
		}
	}		
		
	if ($found == 0) {
		echo"Error: Task not found.";
	} 
	
} elseif (isset($_GET['submit']) && $_GET['action'] == 'update' && !empty($_GET['id']) && !empty($_GET['content'])) {
#update task
	$taskid=htmlspecialchars($_GET['id']);
	$value=htmlspecialchars($_GET['content']);
	foreach ($json_a as $item => $task) {
		if ($item == $taskid) {
			$found = 1;
			$current = file_get_contents($file);
			$current = json_decode($current, TRUE);
			$json_a = array($taskid => array("task" => $value, "status" => "open"));
			$replaced = array_replace_recursive($current, $json_a);
			$replaced = json_encode($replaced);
			if(file_put_contents($file, $replaced, LOCK_EX)) {
				echo"Görev güncellendi..";
				echo"<a href=\"index.php\">Lütfen Buraya Tıklayınız.</a>";
				?>
				<script type="text/javascript">
				window.location = "index.php"
				</script>
				<?php
			} else {
				echo"failure.";
			}
		}
	}
	if ($found==0) {
		echo "Hata! Görev Bulunamadı<a href=\"index.php\"></a>";
	}
	
} elseif (isset($_GET['submit']) && $_GET['action'] == 'add' && !empty($_GET['content'])) {
	
	$id=substr(md5(rand()), 0, 20);
	$value=htmlspecialchars($_GET['content']);	
	$current = file_get_contents($file);
	$current = json_decode($current, TRUE);
	$json_a = array($id => array("task" => $value, "status" => "open"));
	if(is_array($current)) {
		$current = array_merge_recursive($json_a, $current);
	} else {
		$current = $json_a;
	}
	$current=json_encode($current);	
	if(file_put_contents($file, $current, LOCK_EX)) {
		echo"Görev Eklendi<br />";
		echo"<a href=\"index.php\">Lütfen Tıklayınız.</a>";
		?>
		<script type="text/javascript">
		window.location = "index.php"
		</script>
		<?php
	} else {
		echo"failure.";
	}
} elseif (isset($_GET['action']) && $_GET['action'] == 'done' && !empty($_GET['id'])) {
	
	$taskid=htmlspecialchars($_GET['id']);
	foreach ($json_a as $item => $task) {
		if ($item == $taskid) {
			$found = 1;
			$current = file_get_contents($file);
			$current = json_decode($current, TRUE);
			$json_a = array($taskid => array("task" => $task['task'], "status" => "closed"));
			$done = array_replace_recursive($current, $json_a);
			$done = json_encode($done);
			if(file_put_contents($file, $done, LOCK_EX)) {
				echo"";
				echo"<a href="/index.php/">Lütfen Tıklayınız .</a>";
				?>
				<script type="text/javascript">
				window.location = "index.php"
				</script>
				<?php
			} else {
				echo"failure.";
			}
		}
	}
	if ($found==0) {
		echo "Hata Görev Bulunamadı <a href=\"index.php\"></a>";
	}
} elseif (isset($_GET['action']) && $_GET['action'] == 'delete' && !empty($_GET['id'])) {

	$taskid=htmlspecialchars($_GET['id']);
	foreach ($json_a as $item => $task) {
		if ($item == $taskid) {
			$found = 1;
			$current = file_get_contents($file);
			$current = json_decode($current, TRUE);
			unset($current[$taskid]);
			$deleted = json_encode($current);		
			if(file_put_contents($file, $deleted, LOCK_EX)) {
				echo"Görev Silindi ";
				echo"<a href=\"index.php\">Lütfen Tıklayınız</a>";
				?>
				<script type="text/javascript">
					window.location = "index.php"
				</script>
				<?php
			} else {
				echo"failure.";
			}
		}
	}
	if ($found==0) {
		echo "Bulunan Görev Yok.";
	}
} else {
	echo"Yapacak Birşey Yok";
}	

?>
