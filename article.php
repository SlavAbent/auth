<?php	
	session_start();
	include_once('function.php');
	$db = connect_db();

	$fname = $_GET['f'];	

	if($fname != ''){
		$sql = "SELECT * FROM article WHERE id_post=$fname";
		$query = $db->prepare($sql);
		$query->execute();

		if($query->errorCode() != PDO::ERR_NONE){//блок поиска ошибок
			$info = $query->errorInfo();
			echo "Ошибка!<br>";
			file_put_contents("error.log", implode(',', $info) . "\n", FILE_APPEND);			
			exit();
		}

		$post = $query->fetch();

		if($post == false){//MySQL вернула пустой результат (т.е. ноль строк)
			header("Location: index.php");
			exit();
		}

	}else{
		header("Location: index.php");
		exit();			
	}	

?>
<!Doctype html>
<html>
	<head>
		<title><? echo $post['title']; ?></title>
	</head>
	<body>
<?
		echo "<h1>" . $post['title'] . "</h1>";
		echo "<div>" . $post['content'] . "</div><br>";
		echo "<em>Дата:</em> " . $post['dt_post'] . "<br>";		

		if(is_auth()){
			echo '<a href="edit.php?f=' . $post['id_post'] . '">Редактировать</a><br>';
			echo '<a href="delete.php?f=' . $post['id_post'] . '">Удалить</a><br>';				
		}
?>
    <a href="index.php">Назад</a>
</body>
</html>	