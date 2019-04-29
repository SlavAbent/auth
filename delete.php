<?php
	session_start();
	include_once('function.php');
	$db = connect_db();	
	
	$answer = '';
	if(is_auth()){	
		if(isset($_GET['f'])){
			$fname = $_GET['f'];			

			$sql = "DELETE FROM article WHERE id_post=$fname";
			$query = $db->exec($sql);

			if($query == 0){//MySQL вернула удалено 0 строк, т.е. по id_post=$fname в базе несего нет
				header("Location: index.php");
				exit();
			}			

			$answer = "Профиль $fname успешно удалена!<br>";

		}else{
			header('Location: index.php');
			exit();
		}

	}else{
		header('Location: login.php');
		exit();				
	}        

?>
<!doctype html>
<html>
<head>
    <title><?=$answer?></title>
</head>
<body>
	<?=$answer?>		
    <a href="index.php">К списку профилей</a>
</body>
</html>	